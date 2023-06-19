<?php

namespace App\Imports;

use App\Models\DataFile;

interface ImportInterface
{
    public function prepareRow(array $row): array;
    public function filterRow(array $row): bool;
}
