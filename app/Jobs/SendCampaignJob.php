<?php

namespace App\Jobs;

use App\Data\buildSmsData;
use App\Data\CampaignSendToBuildSmsData;
use App\Enums\LogContextEnum;
use App\Models\SmsCampaignSend;
use App\Services\BalanceService;
use App\Services\SmsCampaignContactService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private SmsCampaignSend $campaignSend;

    public function __construct(SmsCampaignSend $campaignSend)
    {
        $this->campaignSend = $campaignSend;
    }

    public function handle(): void
    {
        Log::info('Sending campaign: ' . $this->campaignSend->id);
        //get contacts
        $contacts = SmsCampaignContactService::getContacts($this->campaignSend);
        //get balance
//        BalanceService::getTeamBalance($this->campaignSend->campaign->team_id);

        //submit to sms build queue
        $i = 0;
        foreach ($contacts as $contact) {
            $data = CampaignSendToBuildSmsData::from([...$contact,
                'counter' => $i,
                'country_id' => $contact['country_id'],
                'sms_campaign_send_id' => $this->campaignSend->id,
                'sms_campaign_id' => $this->campaignSend->sms_campaign_id,
                'team_id' => $this->campaignSend->campaign->team_id,
                'sms_routing_plan_id' => $this->campaignSend->getRoutingPlan(),
            ]);
            Log::debug('Sending campaign: ' . $this->campaignSend->id . ' dto: ' . $data->toJson(),
                LogContextEnum::sendCampaignContext());
            buildSmsJob::dispatch($data);
        }
    }
}
