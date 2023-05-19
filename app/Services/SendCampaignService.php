<?php

namespace App\Services;

use App\Jobs\SendCampaignJob;
use App\Models\SmsCampaign;

class SendCampaignService
{
    public static function send(SmsCampaign $campaign)
    {
        $campaignSend = $campaign->sends()->create([
            'status' => 'sending',
            'meta' => $campaign->meta,
        ]);

        SendCampaignJob::dispatch($campaignSend);
    }
}
