<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsRouteSmppConnection extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = [
        'url',
        'username',
        'password',
        'port',
        'dlr_url',
        'dlr_port',
    ];

    public function route()
    {
        return $this->morphOne(SmsRoute::class, 'connection');
    }
}
