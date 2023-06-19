<?php

namespace App\Traits;

trait HasContactCustomInt
{
    public function getCustomIntArray($row, $columns): array
    {
        $data = [];

        for ($i = 1; $i <= 5; $i++) {
            $data["custom{$i}_int"] = $row[$columns["custom{$i}_int"] ?? -1] ?? null;
        }

        $data = array_filter($data, function ($value) {
            return $value !== null;
        });

        return array_map(function ($value) {
            return (int) $value;
        }, $data);
    }
}
