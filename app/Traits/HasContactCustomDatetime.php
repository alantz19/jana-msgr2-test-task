<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasContactCustomDatetime
{
    public function getCustomDatetimeArray($row, $columns): array
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
