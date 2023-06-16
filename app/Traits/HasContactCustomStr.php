<?php

namespace App\Traits;

trait HasContactCustomStr
{
    public function getCustomStrArray($row, $columns): array
    {
        $data = [];

        for ($i = 1; $i <= 5; $i++) {
            $data["custom{$i}_str"] = $row[$columns["custom{$i}_str"] ?? -1] ?? null;
        }

        return array_filter($data, function ($value) {
            return $value !== null;
        });
    }
}
