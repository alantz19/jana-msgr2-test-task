<?php

namespace App\Services;

use Nnjeim\World\World;

class CountryService
{
    public static function guessCountry($countryName)
    {
        $countryName = strtolower(trim($countryName, "_-\t\n \r\0\"'"));
        if (empty($countryName)) {
            return false;
        }

        if ($countryName == 'uk') {
            $countryName = 'gb';
        }

        if (is_numeric($countryName)) {
            $country = World::countries([
                'filters' => [
                    'id' => $countryName,
                ]
            ]);

            if ($country->data->count() > 0) {
                return $country->data->first()['id'];
            }
        }

        if (strlen($countryName) === 2) {
            $country = World::countries([
                'filters' => [
                    'iso2' => strtoupper($countryName),
                ]
            ]);

            if ($country->data->count() > 0) {
                return $country->data->first()['id'];
            }
        }

        if (strlen($countryName) === 3) {
            $country = World::countries([
                'filters' => [
                    'iso3' => strtoupper($countryName),
                ]
            ]);

            if ($country->data->count() > 0) {
                return $country->data->first()['id'];
            }
        }

        $country = World::countries([
            'filters' => [
                'name' => ucwords($countryName),
            ]
        ]);

        if ($country->data->count() > 0) {
            return $country->data->first()['id'];
        }

        throw new \Exception("Country [{$countryName}] not found");
    }
    
}
