<?php

namespace App\Models;

use ClickHouseDB\Query\Query;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Segment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'team_id',
        'type',
        'name',
        'meta',
        'status_id',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
