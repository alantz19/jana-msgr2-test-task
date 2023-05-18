<?php

namespace Tests\Feature;

use App\Models\SmsCampaign;
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

    public function test_create_campaign()
    {
        $campaign = SmsCampaign::factory()->create([
            'team_id' => $this->user->currentTeam->id,
        ]);

//        $campaign->addList(1);

        SmsCampaignText::factory()->count(5)->create([
            'campaign_id' => $campaign->id,
        ]);

//        SmsCampaignSenderId::factory()->count(5)->create([
//            'campaign_id' => $campaign->id,
//        ]);
    }
}
