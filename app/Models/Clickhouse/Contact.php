<?php

namespace App\Models\Clickhouse;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpClickHouseLaravel\BaseModel;

class Contact extends BaseModel
{
    use HasFactory;

    public function newCollection(array $models = []): Collection
    {
        return new Collection($models);
    }
}
