<?php

namespace App\Models;

use App\Traits\HasMeta;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $meta
 */
class SmsCampaign extends Model
{
    use HasMeta;
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'sms_campaign_plan_id',
        'name',
        'meta',
    ];

    protected static function boot()
    {
        static::creating(function ($model) {
            if (empty($model->team_id) && auth()->user()) {
                $model->team_id = auth()->user()->currentTeam->id;
            }
        });

        parent::boot(); // TODO: Change the autogenerated stub
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function sends()
    {
        return $this->hasMany(SmsCampaignSend::class, 'sms_campaign_id');
    }

    public function setLists(array $list_ids)
    {
        $this->addMeta('lists', $list_ids);
    }


    public function setSettings(array $array)
    {
        $this->addMeta('settings', $array);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_campaign', 'sms_campaign_id', 'offer_id');
    }

    public function hasAutosender()
    {
        return $this->getMeta()['autosender_settings'] ?? false;
    }
}
