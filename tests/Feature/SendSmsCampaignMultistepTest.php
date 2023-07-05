<?php

namespace Tests\Feature;

use App\Data\CampaignMultistepSettingsData;
use App\Enums\SmsCampaignStatusEnum;
use App\Models\Clickhouse\Contact;
use App\Models\Lists;
use App\Models\Offer;
use App\Models\SmsCampaign;
use App\Models\SmsCampaignSend;
use App\Models\SmsCampaignSenderid;
use App\Models\SmsCampaignText;
use App\Models\User;
use App\Services\CountryService;
use App\Services\SendCampaignService;
use Database\Factories\SendSmsCampaignFactory;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SendSmsCampaignMultistepTest extends TestCase
{
    public $user;

    public function test_send_multistep_campaign_finished()
    {
        $res = SendSmsCampaignFactory::new()->withBasicSetup(10);

        /** @var SmsCampaign $campaign */
        $campaign = $res['campaign'];
        $campaign->setSettingsMultistep(
            CampaignMultistepSettingsData::from([
                'min_ctr' => 77,
                'step_size' => 100,
            ])
        );
        SendCampaignService::send($campaign);
        $this->assertDatabaseHas('sms_sendlogs', [
            'sms_campaign_id' => $res['campaign']->id,
        ], 'clickhouse');
        $campaignSend = SmsCampaignSend::where(['sms_campaign_id' => $campaign->id])->first();
        $this->assertEquals($campaignSend->getMultistepStatus()->status, SmsCampaignStatusEnum::sent()->value);
    }

    public function test_send_campaign_with_shortener()
    {
        $res = Http::get('http://shorty_php:8081');
        dd($res);
        $res = SendSmsCampaignFactory::new()->withBasicSetup();
        $res['texts']->each(function ($model) use ($res) {
            $model->update([
                'text' => 'Test text {domain}',
            ]);
        });
        SendCampaignService::send($res['campaign']);
        $this->assertDatabaseHas('sms_sendlogs', [
            'sms_campaign_id' => $res['campaign']->id,
        ]);
    }
}
