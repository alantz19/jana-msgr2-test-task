<?php

namespace Tests\Feature;

use App\Models\SmsCampaignPlan;
use App\Models\User;
use App\Services\SmsCampaignPlanService;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CampaignAutosenderTest extends TestCase
{
    public function testAutosenderCreate()
    {
//        self::markTestIncomplete();
//        self::markTestSkipped();
//        $user = User::factory()->withPersonalTeam()->create();
//        $plan = SmsCampaignPlan::factory()->create([
//            'team_id' => $user->currentTeam->id,
//        ]);
//
        $this->assertEquals(true, true);//continue here
//        SmsCampaignPlanService::createCampaignCron($plan);
    }
}
