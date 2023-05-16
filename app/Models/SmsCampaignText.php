<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsCampaignText extends Model
{
    use SoftDeletes;
    use HasUuids;

    protected $fillable = [
        'text',
    ];
}
