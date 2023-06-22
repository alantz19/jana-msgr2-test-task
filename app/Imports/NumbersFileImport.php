<?php

namespace App\Imports;

use App\Models\Clickhouse\Contact;
use App\Services\CountryService;
use App\Services\NumberService;
use App\Traits\HasContactCustomDatetime;
use App\Traits\HasContactCustomDec;
use App\Traits\HasContactCustomInt;
use App\Traits\HasContactCustomStr;
use Ramsey\Uuid\Nonstandard\Uuid;

class NumbersFileImport extends BaseImport
{
    use HasContactCustomStr,
        HasContactCustomInt,
        HasContactCustomDatetime,
        HasContactCustomDec;

    protected array $countriesCache = [];

    public function prepareRow($row): array
    {
        $fields = [];

        $fields['country_id'] = $this->getCountryId($row[$this->columns['country'] ?? -1] ?? '');

        if (empty($fields['country_id'])) {
            return [];
        }

        $fields['phone_normalized'] = $this->getNumber($row[$this->columns['number'] ?? -1] ?? '', $fields['country_id']);

        if (empty($fields['phone_normalized'])) {
            return [];
        }

        $isLogical = true;
        $isMobile = NumberService::isMobile($fields['phone_normalized'], $fields['country_id']);

        if ($this->isLogicalTest()) {
            $isLogical = NumberService::isLogicalNumber($fields['phone_normalized']);
        }

        if ($isMobile && $isLogical) {
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
            'team_id' => $this->dataFile->user->current_team_id,
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
        $res = !empty($row['phone_normalized'])
            && !empty($row['country_id']);

        if (empty($res)) {
            $this->log('Empty phone or country', [
                'row' => $row,
            ]);
        }

        return $res;
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
                if (strlen($normalized) > 9){
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
            $countryId = (int) CountryService::guessCountry($rawValue);
        } catch (\Exception) {
        }

        if (empty($countryId)) {
            return null;
        }

        $this->countriesCache[$rawValue] = $countryId;

        return $countryId;
    }
}
