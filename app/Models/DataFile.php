<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataFile extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    const TYPE_NUMBERS_FILE = 1;
    const TYPE_EMAIL_FILE = 2;

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'path',
        'size',
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
}
