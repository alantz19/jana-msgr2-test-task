<?php

namespace App\Services;

use App\Exceptions\CampaignSendException;
use App\Jobs\SendCampaignJob;
use App\Models\SmsCampaign;
use Illuminate\Support\Facades\Log;

class SendCampaignService
{
    public static function send(SmsCampaign $campaign)
    {
        $campaign->getSettings();
        self::isReadyToSend($campaign);
        $campaignSend = $campaign->sends()->create([
            'status' => 'sending',
            'meta' => $campaign->meta,
        ]);

        Log::debug('Sending campaign_send: ' . $campaignSend->id);
        SendCampaignJob::dispatch($campaignSend);
    }

    private static function isReadyToSend(SmsCampaign $campaign)
    {
        $settings = $campaign->getSettings();
        if (!isset($settings['sms_routing_plan_id'])) {
            Log::debug('No routing plan set for campaign: ' . $campaign->id);
            throw new CampaignSendException('No routing plan set for campaign: ' . $campaign->id);
        }

        return true;
    }
}
