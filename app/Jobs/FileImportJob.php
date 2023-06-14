<?php

namespace App\Jobs;

use App\Models\DataFile;
use App\Services\CountryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

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

        $filePath = $dataFile->path;
        $meta = $dataFile->meta;

        if (empty($meta) || empty($meta['columns'])) {
            Log::critical('FileImportJob', [
                'message' => 'meta is empty or meta[columns] is empty',
                'meta' => $meta,
            ]);

            return;
        }

        LazyCollection::make(function () use ($filePath) {
            $handle = fopen($filePath, 'r');

            while (($raw_string = fgets($handle)) !== false) {
                $row = str_getcsv($raw_string, ';');
                yield $row;
            }
        })
            ->chunk(1000)
            ->each(function (LazyCollection $chunk) use ($dataFile) {
                $rows = $chunk->toArray();

                $data = [];
                $columns = $dataFile->meta['columns'];

                foreach ($rows as $lineNumber => $row) {
                    if (empty($row)) {
                        continue;
                    }

                    $data[] = match ($dataFile->type) {
                        DataFile::TYPE_SMS_FILE => $this->csvSmsRow($row, $columns, $lineNumber),
                        DataFile::TYPE_EMAIL_FILE => $this->csvEmailRow($row, $columns, $lineNumber),
                    };
                }

                // @TODO save data to database
            });
    }

    private function csvSmsRow(array $row, mixed $columns, int $lineNumber): array
    {
        $number = null;
        $countryId = null;

        if (isset($row[$columns['numberCol']])) {
            $number = preg_replace('/\D/', '', $row[$columns['numberCol']]);
        }

        if (isset($row[$columns['countryCol']])) {
            try {
                $countryId = CountryService::guessCountry($row[$columns['countryCol']]);
            } catch (\Exception) {
            }
        }

        // @TODO implement other columns

        return [
            'number' => $number,
            'country_id' => $countryId,
        ];
    }

    private function csvEmailRow(array $row, mixed $columns, int $lineNumber): array
    {
        // TODO: Implement csvEmailRow() method.
        return [];
    }
}
