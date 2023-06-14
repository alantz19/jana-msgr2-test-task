<?php

namespace App\Imports;

use App\Services\CountryService;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class NumbersImport implements
    ToModel,
    WithBatchInserts,
    WithChunkReading,
    WithValidation,
    SkipsOnFailure,
    SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    public function __construct(private readonly array $cols)
    {
    }

    public function rules(): array
    {
        return [
            'number' => 'required|string|min:10',
            'country' => 'numeric|nullable|exists:world_countries,id',
            // @TODO add validation for other columns
        ];
    }

    public function prepareForValidation($data, $index): array
    {
        $array = [];
        $array['number'] = (string) preg_replace('/\D/', '', $data[$this->cols['number']]);

        try {
            $array['country'] = CountryService::guessCountry($data[$this->cols['country']]);
        } catch (\Exception) {
        }

        if (empty($array['country'])) {
            $array['country'] = null;
        }

        // @TODO add other columns

        return $array;
    }

    public function model(array $row)
    {
        dump($row);
        return null;

        return new Number([
            'number' => $row['number'],
            'description' => $row['description'],
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function onError(\Throwable $e)
    {
        $this->errors[] = [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures[] = $failures;
    }
}
