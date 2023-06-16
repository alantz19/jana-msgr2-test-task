<?php

namespace App\Imports;

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
    protected array $firstRows = [];

    public function __construct(DataFile $dataFile)
    {
        $this->dataFile = $dataFile;
        $this->columns = $dataFile->meta['columns'] ?? [];
        $this->list = $this->findOrNewList();
        $this->storagePath = storage_path('app/users/' . $this->dataFile->user_id . '/data-files');
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
            throw InvalidAttributesException::for(
                $this->dataFile::class,
                $this->dataFile->toArray(),
                [
                    'meta' => 'meta is empty or meta[columns] is empty'
                ]
            );
        }

        Log::info('Start import', [
            'data_file_id' => $this->dataFile->id,
            'columns' => $this->columns,
        ]);

        try {
            $this->filePath = $this->csvFilePath();

            return $this->lazyRead($chunkSize, $skip)
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
                        Log::info('Empty chunk after filter', [
                            'data_file_id' => $this->dataFile->id,
                            'chunk' => $chunk->toArray(),
                        ]);
                    }
                });
        } catch (\Exception $e) {
            Log::error('Error while reading file', [
                'dataFile' => $this->dataFile->toArray(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

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

        Log::debug('Converting file to csv', [
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

        Log::debug('File converted', [
            'file' => $csvFile,
        ]);

        return str_replace(storage_path('app/'), '', $csvFile);
    }

    private function getFirstRows($num = 15): array
    {
        if (!empty($this->firstRows)) {
            return $this->firstRows;
        }

        $file = new \SplFileObject($this->filePath);
        $file->seek(PHP_INT_MAX);
        $total = $file->key();
        $size = min($num, $total);

        for ($i = 0; $i <= $size; $i++) {
            $file->seek($i);
            $this->firstRows[] = $file->current();
        }

        $file = null;

        return $this->firstRows;
    }

    private function findOrNewList(): ?Lists
    {
        if (empty($this->dataFile->meta['list_id']) && empty($this->dataFile->meta['list_name'])) {
            return null;
        }

        if (!empty($this->dataFile->meta['list_id'])) {
            return Lists::findOrFail($this->dataFile->meta['list_id']);
        }

        $list = new Lists([
            'team_id' => $this->dataFile->user->current_team_id,
            'name' => $dataFile->meta['list_name'] ?? $this->dataFile->name,
        ]);

        $list->saveOrFail();

        Log::info('List created', [
            'list' => $list->toArray(),
        ]);

        return $list;
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
}
