<?php

namespace App\Imports;

use App\Models\Contact;
use App\Services\CountryService;
use App\Services\NumberService;
use Carbon\Carbon;
use Ramsey\Uuid\Nonstandard\Uuid;

class ContactsImport extends BaseImport
{
    protected array $countriesCache = [];

    public function prepareRow($row): array
    {
        $fields = [];

        $fields['country_id'] = $this->getCountryId($row[$this->columns['country'] ?? -1] ?? '');

        if (!empty($fields['country_id'])) {
            $fields['phone_normalized'] = $this->getNumber(
                $row[$this->columns['number'] ?? -1] ?? '',
                $fields['country_id']
            );
        }

        $fields['email_normalized'] = $this->getEmail($row[$this->columns['email'] ?? -1] ?? '');

        if (empty($fields['phone_normalized']) && empty($fields['email_normalized'])) {
            $this->log('Empty email or phone,country', [
                'row' => $row,
            ]);
            return [];
        }

        $isLogicalNumber = true;
        $isMobile = NumberService::isMobile($fields['phone_normalized'], $fields['country_id']);

        if ($this->isLogicalTest()) {
            $isLogicalNumber = NumberService::isLogicalNumber($fields['phone_normalized']);
        }

        if ($isMobile && $isLogicalNumber) {
            $fields['phone_is_good'] = 1;
        }

        if ($row[$this->columns['name'] ?? -1] ?? false) {
            $fields['name'] = $row[$this->columns['name']];
        }

        $customStrArray = $this->getCustomStrArray($row, $this->columns);
        $customIntArray = $this->getCustomIntArray($row, $this->columns);
        $customDecArray = $this->getCustomDecArray($row, $this->columns);
        $customDatetimeArray = $this->getCustomDatetimeArray($row, $this->columns);

        return [
            'id' => Uuid::uuid4()->toString(),
            'team_id' => $this->dataFile->team_id,
            'list_id' => $this->list->id,
            ...$fields,
            ...$customStrArray,
            ...$customIntArray,
            ...$customDecArray,
            ...$customDatetimeArray,
        ];
    }

    public function filterRow(array $row): bool
    {
        return !empty($row['email_normalized'])
            || (!empty($row['phone_normalized']) && !empty($row['country_id']));
    }

    public function saveChunk(array $records): void
    {
        parent::saveChunk($records);

        Contact::insertAssoc($records);
    }

    private function getNumber(string $rawValue, int $countryId): ?string
    {
        $normalized = null;

        if ($phoneNumber = NumberService::normalize($rawValue, $countryId)) {
            $normalized = $phoneNumber->getCountryCode() . $phoneNumber->getNationalNumber();

            // <<<hack from old code
            if ($phoneNumber->getCountryCode() == 46) {
                if (strlen($normalized) > 9) {
                    $normalized = preg_replace('/^4646/', '46', $normalized);
                }
            }

            if ($phoneNumber->getCountryCode() == '49') {
                $normalized = preg_replace('/^4949/', '49', $normalized);
            }
            // end hack>>>
        }

        return $normalized;
    }

    private function getEmail(string $rawValue): ?string
    {
        if (empty($rawValue)) {
            return null;
        }

        $email = mb_strtolower(trim($rawValue));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        return $email;
    }

    private function getCountryId(?string $rawValue): ?int
    {
        if (empty($rawValue)) {
            return null;
        }

        if ($this->dataFile->meta['fixedCountryId'] ?? false) {
            return $this->dataFile->meta['fixedCountryId'];
        }

        if (isset($this->countriesCache[$rawValue])) {
            return $this->countriesCache[$rawValue];
        }

        $countryId = null;

        try {
            $countryId = (int)CountryService::guessCountry($rawValue);
        } catch (\Exception) {
        }

        if (empty($countryId)) {
            return null;
        }

        $this->countriesCache[$rawValue] = $countryId;

        return $countryId;
    }

    private function getCustomStrArray($row, $columns): array
    {
        $data = [];

        for ($i = 1; $i <= 5; $i++) {
            $data["custom{$i}_str"] = $row[$columns["custom{$i}_str"] ?? -1] ?? null;
        }

        return array_filter($data, function ($value) {
            return $value !== null;
        });
    }

    private function getCustomIntArray($row, $columns): array
    {
        $data = [];

        for ($i = 1; $i <= 5; $i++) {
            $data["custom{$i}_int"] = $row[$columns["custom{$i}_int"] ?? -1] ?? null;
        }

        $data = array_filter($data, function ($value) {
            return $value !== null;
        });

        return array_map(function ($value) {
            return (int)$value;
        }, $data);
    }

    private function getCustomDecArray($row, $columns): array
    {
        $data = [];

        for ($i = 1; $i <= 2; $i++) {
            $data["custom{$i}_dec"] = $row[$columns["custom{$i}_dec"] ?? -1] ?? null;
        }

        $data = array_filter($data, function ($value) {
            return $value !== null;
        });

        return array_map(function ($value) {
            return (float)$value;
        }, $data);
    }

    private function getCustomDatetimeArray($row, $columns): array
    {
        $data = [];

        for ($i = 1; $i <= 5; $i++) {
            $data["custom{$i}_datetime"] = $row[$columns["custom{$i}_datetime"] ?? -1] ?? null;
        }

        $data = array_map(function ($value) {
            if ($value) {
                try {
                    return Carbon::parse($value)->toDateTimeString();
                } catch (\Exception) {
                    return null;
                }
            }

            return null;
        }, $data);

        return array_filter($data, function ($value) {
            return $value !== null && $value != '1970-01-01 00:00:00';
        });
    }
}
