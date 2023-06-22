<?php

namespace App\Models;

use ClickHouseDB\Query\Query;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use HasFactory, HasUuids;

    protected ?Query $query = null;

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'meta',
        'status_id',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
