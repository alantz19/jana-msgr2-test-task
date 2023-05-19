<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsCampaignSend extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'status',
        'meta'
    ];

    public function campaign(): HasOne
    {
        return $this->hasOne(SmsCampaign::class);
    }

    public function getLimit()
    {
        return 100;
    }

    public function getLists()
    {
        return $this->getMeta()['lists'];
    }

    public function getMeta()
    {
        $meta = json_decode($this->meta, true);

        return $meta ? $meta : [];
    }
}
