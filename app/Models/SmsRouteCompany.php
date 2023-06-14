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

    protected $hidden = ['meta'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        static::creating(function ($model) {
            $model->team_id = auth()->user()->currentTeam->id;
        });
    }
}
