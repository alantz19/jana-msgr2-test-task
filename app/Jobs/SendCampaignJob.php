<?php

namespace App\Jobs;

use App\Dto\buildSmsDto;
use App\Models\SmsCampaignSend;
use App\Services\SmsCampaignContactService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        \Log::info('Sending campaign: ' . $this->campaignSend->id);
        //get contacts
        $contacts = SmsCampaignContactService::getContacts($this->campaignSend);
        //get balance

        //submit to sms build queue
        $i = 0;
        foreach ($contacts as $contact) {
            $dto = buildSmsDto::from(
                [...$contact,
                    'counter' => $i,
                    'sms_campaign_send_id' => $this->campaignSend->id,
                    'sms_campaign_id' => $this->campaignSend->sms_campaign_id,
                    'team_id' => $this->campaignSend->campaign->team_id
                ]);
            buildSmsJob::dispatch($dto);
        }
    }
}
