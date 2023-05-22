<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsRoute extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = [
        'team_id',
        'name',
        'route_company_id',
        'meta'
    ];

    public function smppConnections() : MorphTo
    {
        return $this->morphTo('connection');
    }
}
