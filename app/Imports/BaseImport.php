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
    protected Lists $list;
    protected string $filePath;
    protected array $columns;
    protected string $delimiter = ';';

    public function __construct(DataFile $dataFile)
    {
        if (empty($dataFile->meta) || empty($dataFile->meta['columns'])) {
            throw InvalidAttributesException::for(
                $dataFile::class,
                $dataFile->toArray(),
                [
                    'meta' => 'meta is empty or meta[columns] is empty'
                ]
            );
        }

        $this->dataFile = $dataFile;
        $this->columns = $dataFile->meta['columns'];
        $this->list = $this->findList();
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
        try {
            $this->filePath = $this->dataFile->path;
            $ext = pathinfo($this->dataFile->path, PATHINFO_EXTENSION);

            if (in_array($ext, ['xls', 'xlsx'])) {
                $this->filePath = $this->xls2csv();
            }

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
                        Log::info('Empty chunk', [
                            'data_file_id' => $this->dataFile->id,
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
        $fileName = $pathInfo['filename'] . '_chunks.csv';

        $fp = fopen($pathInfo['dirname'] . '/' . $fileName, 'w');

        fputcsv($fp, ['### chunk ###'], $this->delimiter);

        foreach ($records as $record) {
            fputcsv($fp, $record, $this->delimiter);
        }

        fclose($fp);
    }

    public function getList(): Lists
    {
        return $this->list;
    }

    private function findList(): Lists
    {
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

    private function xls2csv(): string
    {
        if (!file_exists($this->dataFile->path)) {
            throw new \Exception('File not found');
        }

        if (!is_readable($this->dataFile->path)) {
            throw new \Exception('File not readable');
        }

        $fileInfo = pathinfo($this->dataFile->path);

        if (!in_array($fileInfo['extension'], ['xls', 'xlsx'])) {
            throw new \Exception('File is not xls');
        }

        Log::debug('Converting file to csv', [
            'file' => $this->dataFile->path
        ]);

        $csvFileName = $fileInfo['filename'] . '_' . time() . '.csv';
        $csvFilePath = storage_path('app/data/xls2csv/' . $this->dataFile->user_id);

        if (!file_exists($csvFilePath)) {
            mkdir($csvFilePath, 0775, true);
        }

        $csvFile = $csvFilePath . '/' . $csvFileName;

        $reader = match ($fileInfo['extension']) {
            'xls' => new \PhpOffice\PhpSpreadsheet\Reader\Xls(),
            'xlsx' => new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(),
        };
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->dataFile->path);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer->setDelimiter(';');
        $writer->setEnclosure('');
        $writer->setLineEnding("\r\n");
        $writer->save($csvFile);

        Log::debug('File converted', [
            'file' => $csvFile,
        ]);

        return $csvFile;
    }
}
