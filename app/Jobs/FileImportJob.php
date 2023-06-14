<?php

namespace App\Jobs;

use App\Imports\NumbersImport;
use App\Models\DataFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FileImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly int $fileId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dataFile = DataFile::findOrFail($this->fileId);

        Log::info('start FileImportJob', [
            'dataFile' => $dataFile->toArray(),
        ]);

        if (empty($dataFile->meta) || empty($dataFile->meta['columns'])) {
            Log::critical('FileImportJob', [
                'message' => 'meta is empty or meta[columns] is empty',
                'meta' => $dataFile->meta,
            ]);

            return;
        }

        $import = match ($dataFile->type) {
            DataFile::TYPE_NUMBERS_FILE => new NumbersImport($dataFile->meta['columns']),
//            DataFile::TYPE_EMAIL_FILE => $this->importEmailFile($filePath, $meta['columns']),
        };

        $import->import($dataFile->path);

        Log::info('import errors', [
            'errors' => $import->errors()->toArray(),
        ]);

        Log::info('import failures', [
            'failures' => $import->failures()->toArray(),
        ]);

        Log::info('end FileImportJob', [
            'dataFile' => $dataFile->id,
        ]);
    }
}
