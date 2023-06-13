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

    public $priceForCountry;
    protected $fillable = [
        'team_id',
        'name',
        'sms_route_company_id',
        'meta'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        static::creating(function ($model) {
            $model->team_id = auth()->user()->currentTeam->id;
        });

    }

    public function smppConnection(): MorphTo
    {
        return $this->morphTo('connection');
    }

    /**
     * @return MorphOne - SMPP or Highway connection
     */
    public function connection(): MorphTo
    {
        return $this->morphTo();
    }

    public function smsRouteRates()
    {
        return $this->hasMany(SmsRouteRate::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function smsCompany()
    {
        return $this->belongsTo(SmsRouteCompany::class);
    }
}
