<?php

namespace App\Imports;

use App\Enums\DataFileStatusEnum;
use App\Exceptions\InvalidAttributesException;
use App\Models\DataFile;
use App\Models\Lists;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

abstract class BaseImport implements ImportInterface
{
    protected DataFile $dataFile;
    protected ?Lists $list;
    protected string $filePath;
    protected array $columns;
    protected int $chunkIterator = 0;
    protected string $storagePath;
    protected string $delimiter = ',';

    public function __construct(DataFile $dataFile)
    {
        $this->dataFile = $dataFile;
        $this->columns = $dataFile->meta['columns'] ?? [];
        $this->list = $this->findOrCreateList();
        $this->storagePath = storage_path('app/teams/' . $this->dataFile->team_id . '/data-files');

        $this->autoDetectDelimiter();
    }

    public function lazyRead($chunkSize = 1000, $skip = 0): LazyCollection
    {
        return LazyCollection::make(function () {
            $handle = fopen($this->filePath, 'r');

            while (($row = fgetcsv($handle, 4096, $this->delimiter)) !== false) {
                yield $row;
            }

            fclose($handle);
        })
            ->skip($skip)
            ->chunk($chunkSize);
    }

    public function import($chunkSize = 1000, $skip = 0): LazyCollection
    {
        if (empty($this->columns)) {
            $this->setDataFileStatus(DataFileStatusEnum::failed());

            throw InvalidAttributesException::for(
                $this->dataFile::class,
                $this->dataFile->toArray(),
                [
                    'meta' => 'meta is empty or meta[columns] is empty'
                ]
            );
        }

        $this->setDataFileStatus(DataFileStatusEnum::processing());

        $this->log('Start import', [
            'columns' => $this->columns,
            'dataFile' => $this->dataFile->toArray(),
        ]);

        try {
            $this->filePath = $this->csvFilePath();

            $res = $this->lazyRead($chunkSize, $skip)
                ->each(function (LazyCollection $chunk) {
                    $records = $chunk->map(function ($row) {
                        return $this->prepareRow($row);
                    })
                        ->filter(function ($row) {
                            return $this->filterRow($row);
                        })
                        ->toArray();

                    if (!empty($records)) {
                        $this->saveChunk($records);
                    } else {
                        $this->log('Empty chunk after filter', [
                            'chunk' => $chunk->toArray(),
                        ]);
                    }
                });

            $this->setDataFileStatus(DataFileStatusEnum::completed());

            return $res;
        } catch (\Exception $e) {
            $this->setDataFileStatus(DataFileStatusEnum::failed());

            $this->log('Error while reading file', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 'error');

            throw $e;
        }
    }

    public function isLogicalTest(): bool
    {
        return $this->dataFile->meta['logical_test'] ?? false;
    }

    public function saveChunk(array $records): void
    {
        $pathInfo = pathinfo($this->filePath);
        $fileName = $pathInfo['filename'] . '_chunk' . $this->chunkIterator . '.csv';
        $filePath = $this->storagePath . '/chunks/' . $this->dataFile->id;

        if (!file_exists($filePath)) {
            mkdir($filePath, 0775, true);
        }

        $file = $filePath . '/' . $fileName;

        $handle = fopen($file, 'w');

        foreach ($records as $record) {
            fputcsv($handle, $record, $this->delimiter);
        }

        fclose($handle);

        $this->chunkIterator++;
    }

    public function getList(): Lists
    {
        return $this->list;
    }

    public function getDelimiter(): string
    {
        return $this->delimiter;
    }

    public function xls2csv(): string
    {
        $filePath = storage_path('app/' . $this->dataFile->path);

        if (!file_exists($filePath)) {
            throw new \Exception('File not found');
        }

        if (!is_readable($filePath)) {
            throw new \Exception('File not readable');
        }

        $fileInfo = pathinfo($filePath);

        if (!in_array($fileInfo['extension'], ['xls', 'xlsx'])) {
            throw new \Exception('File is not xls');
        }

        $this->log('Start converting file to csv', [
            'file' => $filePath,
        ]);

        $csvFileName = $fileInfo['filename'] . '_' . time() . '.csv';

        if (!file_exists($this->storagePath)) {
            mkdir($this->storagePath, 0775, true);
        }

        $csvFile = $this->storagePath . '/' . $csvFileName;

        $reader = match ($fileInfo['extension']) {
            'xls' => new \PhpOffice\PhpSpreadsheet\Reader\Xls(),
            'xlsx' => new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(),
        };
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer->setDelimiter(',');
        $writer->setEnclosure('');
        $writer->setLineEnding("\r\n");
        $writer->save($csvFile);

        $this->log('File converted', [
            'file' => $csvFile,
        ]);

        // assign to temp meta variable due to "Indirect modification of overloaded property" error
        $meta = $this->dataFile->meta;
        $meta['csv_file'] = str_replace(storage_path('app/'), '', $csvFile);
        $this->dataFile->meta = $meta;
        $this->dataFile->save();

        return $this->dataFile->meta['csv_file'];
    }

    public function getSampleRows(): array
    {
        $firstRows = $this->getFirstRows();

        if (empty($firstRows)) {
            return [];
        }

        $response = [
            'rows' => [],
            'cols' => 0,
        ];

        foreach ($firstRows as $row) {
            $arr = str_getcsv($row, $this->delimiter);
            $response['cols'] = max($response['cols'], count($arr));
            $response['rows'][] = $arr;
        }

        return $response;
    }

    private function getFirstRows($num = 15): array
    {
        $filePath = $this->csvFilePath();

        if (!file_exists($filePath)) {
            return [];
        }

        $file = new \SplFileObject($filePath);
        $file->seek(PHP_INT_MAX);
        $total = $file->key();
        $size = min($num, $total);
        $firstRows = [];

        for ($i = 0; $i < $size; $i++) {
            $file->seek($i);
            $firstRows[] = $file->current();
        }

        $file = null;

        return $firstRows;
    }

    private function findOrCreateList(): ?Lists
    {
        if (empty($this->dataFile->meta['list_id']) && empty($this->dataFile->meta['list_name'])) {
            return null;
        }

        if (!empty($this->dataFile->meta['list_id'])) {
            return Lists::findOrFail($this->dataFile->meta['list_id']);
        }

        $list = new Lists([
            'team_id' => $this->dataFile->team_id,
            'name' => $dataFile->meta['list_name'] ?? $this->dataFile->name,
        ]);

        $list->saveOrFail();

        $this->log('List created', [
            'list' => $list->toArray(),
        ]);

        return $list;
    }

    private function autoDetectDelimiter(): void
    {
        $ext = pathinfo($this->dataFile->path, PATHINFO_EXTENSION);

        // delimiter auto detect only for csv files
        if (in_array($ext, ['xls', 'xlsx'])) {
            return;
        }

        $delimiters = [',', ';', "\t", '|'];

        $firstRows = $this->getFirstRows();

        $delimitersCount = array_map(function ($delimiter) use ($firstRows) {
            return count(array_filter($firstRows, function ($row) use ($delimiter) {
                return substr_count($row, $delimiter) > 0;
            }));
        }, $delimiters);

        $delimiterIdx = array_keys($delimitersCount, max($delimitersCount))[0];
        $this->delimiter = $delimiters[$delimiterIdx];

        $this->log('Delimiter auto detected', [
            'delimiter' => $this->delimiter,
        ]);
    }

    private function csvFilePath(): string
    {
        $ext = pathinfo($this->dataFile->path, PATHINFO_EXTENSION);

        if (in_array($ext, ['xls', 'xlsx']) && !empty($this->dataFile['meta']['csv_file'])) {
            return storage_path('app/' . $this->dataFile['meta']['csv_file']);
        }

        $path =  match ($ext) {
            'xls', 'xlsx' => $this->xls2csv(),
            default => $this->dataFile->path,
        };

        return storage_path('app/' . $path);
    }

    protected function setDataFileStatus(DataFileStatusEnum $status): void
    {
        $this->dataFile->status_id = $status->value;
        $this->dataFile->save();
    }

    protected function log(string $message, array $context = [], string $level = 'info'): void
    {
        Log::$level($message, array_merge([
            'data_file_id' => $this->dataFile->id,
        ], $context));
    }
}
