<?php

namespace App\Models;

use App\Enums\DataFileStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataFile extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'path',
        'size',
        'status_id',
        'meta',
    ];

    protected $casts = [
        'type' => 'integer',
        'size' => 'integer',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getColumns(): array
    {
        return $this->meta['columns'] ?? [];
    }

    public function isPending(): bool
    {
        return $this->status_id === DataFileStatusEnum::pending()->value;
    }
}
