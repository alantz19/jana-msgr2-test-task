<?php

namespace App\Traits;

trait HasContactCustomDec
{
    public function getCustomDecArray($row, $columns): array
    {
        $data = [];

        for ($i = 1; $i <= 2; $i++) {
            $data["custom{$i}_dec"] = $row[$columns["custom{$i}_dec"] ?? -1] ?? null;
        }

        $data = array_filter($data, function ($value) {
            return $value !== null;
        });

        return array_map(function ($value) {
            return (float) $value;
        }, $data);
    }
}
