<?php

namespace Tests\Feature;

use App\Models\Clickhouse\Contact;
use App\Models\Lists;
use App\Models\Offer;
use App\Models\SmsCampaign;
use App\Models\SmsCampaignSenderid;
use App\Models\SmsCampaignText;
use App\Models\User;
use App\Services\CountryService;
use App\Services\SendCampaignService;
use Database\Factories\SendSmsCampaignFactory;
use Tests\TestCase;

class SendSmsCampaignTest extends TestCase
{
    public $user;

    public function test_send_campaign()
    {
        $res = SendSmsCampaignFactory::new()->withBasicSetup();
        SendCampaignService::send($res['campaign']);
        $this->assertDatabaseHas('sms_sendlogs', [
            'sms_campaign_id' => $res['campaign']->id,
        ], 'clickhouse');
    }

    public function test_send_campaign_with_shortener()
    {
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
