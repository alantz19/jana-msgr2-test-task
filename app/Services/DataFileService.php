<?php

namespace App\Services;

use App\Models\DataFile;
use Illuminate\Support\Facades\Log;

class DataFileService
{
    public static function xls2csv(DataFile $dataFile): string
    {
        if (!file_exists($dataFile->path)) {
            throw new \Exception('File not found');
        }

        if (!is_readable($dataFile->path)) {
            throw new \Exception('File not readable');
        }

        $fileInfo = pathinfo($dataFile->path);

        if (!in_array($fileInfo['extension'], ['xls', 'xlsx'])) {
            throw new \Exception('File is not xls');
        }

        Log::debug('Converting file to csv', ['file' => $dataFile->path]);

        $csvFileName = $fileInfo['filename'] . '_' . time() . '.csv';
        $csvFilePath = storage_path('app/data/users/' . $dataFile->user_id . '/csv');

        if (!file_exists($csvFilePath)) {
            mkdir($csvFilePath, 0775, true);
        }

        $csvFile = $csvFilePath . '/' . $csvFileName;

        $reader = match ($fileInfo['extension']) {
            'xls' => new \PhpOffice\PhpSpreadsheet\Reader\Xls(),
            'xlsx' => new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(),
        };
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($dataFile->path);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer->setDelimiter(';');
        $writer->setEnclosure('');
        $writer->setLineEnding("\r\n");
        $writer->save($csvFile);

        Log::debug('File converted', ['file' => $csvFile]);

        return $csvFile;
    }
}
