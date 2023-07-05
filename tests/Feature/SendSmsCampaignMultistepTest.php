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
use App\Models\SmsSendlog;
use App\Models\User;
use App\Services\ClickhouseService;
use App\Services\CountryService;
use App\Services\SendCampaignService;
use Database\Factories\SendSmsCampaignFactory;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Helpers\SmsTestHelper;
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

    public function test_send_campaign_with_shortener_with_step_size()
    {
        $res = SendSmsCampaignFactory::new()->withBasicSetup(10);
        $res['texts']->each(function ($model) use ($res) {
            $model->update([
                'text' => 'Test text {domain}',
            ]);
        });
        $campaign = $res['campaign'];
        $campaign->setSettingsMultistep(
            CampaignMultistepSettingsData::from([
                'min_ctr' => 3,
                'step_size' => 5,
            ])
        );
        SendCampaignService::send($res['campaign']);

        //generate clicks
        $campaignSend = SmsCampaignSend::where(['sms_campaign_id' => $campaign->id])->first();

        SmsTestHelper::generateClicks("sms_campaign_send_id = '{$campaignSend->id}'", 30);

        $sentC =
            count(ClickhouseService::query("select * from sms_sendlogs_v where sms_campaign_id = '{$res['campaign']->id}'"));
        $this->assertEquals(5, $sentC, 'Step limit is not considered');

        //call next step cron
        $this->travel(5)->minutes();

        $this->artisan('sms:campaigns-mutistep-send');

        //validate results of next step

        $sentC =
            count(ClickhouseService::query("select * from sms_sendlogs_v where sms_campaign_id = '{$res['campaign']->id}'"));
        $this->assertEquals(10, $sentC, 'Step limit is not considered');
        //generate clicks for new sent

        //call next step cron

        //validate finished status


        $this->assertDatabaseHas('sms_sendlogs', [
            'sms_campaign_id' => $res['campaign']->id,
        ], 'clickhouse');
    }
}
