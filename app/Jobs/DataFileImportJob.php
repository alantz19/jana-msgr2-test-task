<?php

namespace App\Jobs;

use App\Imports\NumbersFileImport;
use App\Models\Contact;
use App\Models\DataFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DataFileImportJob implements ShouldQueue
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
        Log::info('DataFileImportJob start', [
            'data_file_id' => $this->fileId,
        ]);

        $dataFile = DataFile::findOrFail($this->fileId);

        $import = match ($dataFile->type) {
            DataFile::TYPE_NUMBERS_FILE => new NumbersFileImport($dataFile),
//            DataFile::TYPE_EMAIL_FILE => $this->importEmailFile($filePath, $meta['columns']),
        };

        $import->import();

        Log::info('DataFileImportJob end', [
            'data_file_id' => $dataFile->id,
        ]);
    }
}
