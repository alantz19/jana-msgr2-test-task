<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataFile extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    const TYPE_SMS_FILE = 1;
    const TYPE_EMAIL_FILE = 2;

    protected $fillable = [
        'user_id',
        'name',
        'path',
        'size',
        'meta',
    ];

    protected $casts = [
        'size' => 'integer',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
