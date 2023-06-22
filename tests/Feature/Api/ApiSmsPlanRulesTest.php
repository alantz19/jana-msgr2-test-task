<?php

namespace Tests\Feature\Api;

use App\Enums\SmsRoutingPlanRuleActionEnum;
use App\Models\SmsRoutingPlan;
use App\Models\SmsRoutingPlanRule;
use Database\Factories\UserFactory;
use Tests\TestCase;

class ApiSmsPlanRulesTest extends TestCase
{

    public function testIndex(): void
    {
        $user = UserFactory::new()->withPersonalTeam()->create();
        $plan = SmsRoutingPlan::factory()->state([
            'team_id' => $user->current_team_id
        ])->create();
        $rule = SmsRoutingPlanRule::factory()->state([
            'sms_routing_plan_id' => $plan->id
        ])->create();
        $this->actingAs($user);
        $this->assertDatabaseHas('sms_routing_plan_rules', [
            'id' => $rule->id
        ]);
        $this->getJson("/api/v1/sms/routing/plans/{$plan->id}/rules")
            ->assertJsonFragment([
                'id' => $rule->id
            ])->assertOk();
    }

    public function testStore(): void
    {
        $user = UserFactory::new()->withPersonalTeam()->create();
        $plan = SmsRoutingPlan::factory()->state([
            'team_id' => $user->current_team_id
        ])->create();
        $this->actingAs($user);
        $this->postJson("/api/v1/sms/routing/plans/{$plan->id}/rules", [
            'action' => SmsRoutingPlanRuleActionEnum::send()
        ])->assertCreated()->assertJsonFragment([
            'action' => SmsRoutingPlanRuleActionEnum::send()
        ]);
    }

    public function testUpdate(): void
    {
        $user = UserFactory::new()->withPersonalTeam()->create();
        $plan = SmsRoutingPlan::factory()->state([
            'team_id' => $user->current_team_id
        ])->create();
        $rule = SmsRoutingPlanRule::factory()->state([
            'sms_routing_plan_id' => $plan->id
        ])->create();
        $this->actingAs($user);
        $this->putJson("/api/v1/sms/routing/plans/{$plan->id}/rules/{$rule->id}", [
            'action' => SmsRoutingPlanRuleActionEnum::drop()
        ])->assertOk()->assertJsonFragment([
            'action' => SmsRoutingPlanRuleActionEnum::drop()
        ]);
    }

    public function testDestroy(): void
    {
        $user = UserFactory::new()->withPersonalTeam()->create();
        $plan = SmsRoutingPlan::factory()->state([
            'team_id' => $user->current_team_id
        ])->create();
        $rule = SmsRoutingPlanRule::factory()->state([
            'sms_routing_plan_id' => $plan->id
        ])->create();
        $this->actingAs($user);
        $this->deleteJson("/api/v1/sms/routing/plans/{$plan->id}/rules/{$rule->id}")
            ->assertNoContent();
        $this->getJson("/api/v1/sms/routing/plans/{$plan->id}/rules")
            ->assertJsonMissing([
                'id' => $rule->id
            ])->assertOk();
    }
}
