<?php

namespace App\Imports;

use App\Models\Contact;
use App\Services\CountryService;
use App\Services\NumberService;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Nonstandard\Uuid;

class NumbersImport extends BaseImport
{
    public function prepareRow($row): array
    {
        // phone_is_good ?
        // phone_is_good_reason ?

        // @TODO add other columns
        $countryId = $this->getCountryId($row[$this->columns['country']] ?? '');
        $phone = $this->getNumber($row[$this->columns['number']] ?? '', $countryId);

        return [
            'id' => Uuid::uuid4()->toString(),
            'team_id' => $this->dataFile->user->current_team_id,
            'list_id' => $this->list->id,
            'phone_normalized' => $phone,
            'country_id' => $countryId,
        ];
    }

    public function filterRow(array $row): bool
    {
        return !empty($row['phone_normalized'])
            && !empty($row['country_id']);
    }

    public function saveChunk(array $records): void
    {
        parent::saveChunk($records);

        Contact::insertAssoc($records);
    }

    private function getNumber(string $rawValue, int $countryId): ?string
    {
        $number = (string)preg_replace('/\D/', '', $rawValue);

        if ($this->isLogicalTest()) {
            if (!NumberService::isLogicalNumber($number)) {
                Log::info('Number is not logical', [
                    'data_file_id' => $this->dataFile->id,
                    'number' => $number,
                    'raw_value' => $rawValue,
                ]);

                return null;
            }
        }

        // @TODO check with giggsey/libphonenumber-for-php ?

        return $number;
    }

    private function getCountryId(string $rawValue): int
    {
        $countryId = null;

        try {
            if ($this->dataFile->meta['fixedCountryId'] ?? false) {
                $countryId = $this->dataFile->meta['countryId'];
            } else {
                $countryId = CountryService::guessCountry($rawValue);
            }
        } catch (\Exception) {
        }

        if (empty($countryId)) {
            $countryId = null;
        }

        return (int) $countryId;
    }
}
