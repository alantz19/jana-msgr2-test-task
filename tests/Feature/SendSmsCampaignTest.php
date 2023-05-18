<?php

namespace Tests\Feature;

use App\Models\Offer;
use App\Models\SmsCampaign;
use App\Models\SmsCampaignSenderid;
use App\Models\SmsCampaignText;
use App\Models\User;
use Tests\TestCase;

class SendSmsCampaignTest extends TestCase
{
    public $user;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        $this->user = $user;

    }

    public function test_send_campaign()
    {
        $campaign = SmsCampaign::factory()->create([
            'team_id' => $this->user->currentTeam->id,
        ]);

//        $campaign->addList(1);

        SmsCampaignText::factory()->count(5)->create([
            'campaign_id' => $campaign->id,
        ]);

        SmsCampaignSenderId::factory()->count(5)->create([
            'campaign_id' => $campaign->id,
        ]);

        Offer::factory()->count(5)->create([
            'team_id' => $this->user->currentTeam->id,
        ])->each(function($model) use ($campaign) {
//            $campaign->addOffer($model);
        });

        $campaign->setSettings([
            'send_time' => null,
            'sms_amount' => 100
        ]);

        $campaign->send();
    }
}
