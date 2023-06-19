<?php

namespace Tests\Feature;

use App\Models\SmsCampaignPlan;
use App\Models\User;
use App\Services\SmsCampaignPlanService;
use Tests\TestCase;

class CampaignAutosenderTest extends TestCase
{
    public function testAutosenderCreate()
    {
        self::markTestSkipped();
        $user = User::factory()->withPersonalTeam()->create();
        $plan = SmsCampaignPlan::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        $this->assertEquals(true, true);//continue here
        SmsCampaignPlanService::createCampaignCron($plan);
    }
}
