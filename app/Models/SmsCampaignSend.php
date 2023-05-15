<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsCampaignSend extends Model
{
    protected $fillable = [
        'status'
    ];

    public function campaign(): HasOne
    {
        return $this->hasOne(SmsCampaign::class);
    }
}
