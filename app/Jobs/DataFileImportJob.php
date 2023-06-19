<?php

namespace App\Jobs;

use App\Enums\DataFileTypeEnum;
use App\Imports\EmailFileImport;
use App\Imports\NumbersFileImport;
use App\Models\DataFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DataFileImportJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly string $fileId)
    {
    }

    public function uniqueId(): string
    {
        return hash('md5', 'data_file_import_' . $this->fileId);
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
            DataFileTypeEnum::numbers()->value => new NumbersFileImport($dataFile),
            DataFileTypeEnum::emails()->value => new EmailFileImport($dataFile),
        };

        $import->import();

        Log::info('DataFileImportJob end', [
            'data_file_id' => $dataFile->id,
        ]);
    }
}
