<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsRouteCompany extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $fillable = [
        'team_id',
        'name',
        'meta'
    ];
}
