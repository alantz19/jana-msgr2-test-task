<?php

namespace App\Jobs;

use App\Imports\NumbersImport;
use App\Models\Contact;
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

        Log::info('FileImportJob start', [
            'dataFile' => $dataFile->toArray(),
        ]);

        $import = match ($dataFile->type) {
            DataFile::TYPE_NUMBERS_FILE => new NumbersImport($dataFile),
//            DataFile::TYPE_EMAIL_FILE => $this->importEmailFile($filePath, $meta['columns']),
        };

        $import->import();

        Log::info('FileImportJob end', [
            'dataFile' => $dataFile->id,
        ]);
    }
}
