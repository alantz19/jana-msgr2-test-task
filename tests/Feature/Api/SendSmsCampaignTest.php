<?php

namespace Tests\Feature\Api;

use App\Data\SmsRoutingPlanSelectorData;
use App\Models\Contact;
use App\Models\Offer;
use App\Models\SmsCampaign;
use App\Models\SmsRoute;
use App\Models\SmsRoutingPlan;
use App\Services\BalanceService;
use Tests\TestCase;

class SendSmsCampaignTest extends BaseApiTest
{
    public function testListCampaigns()
    {
        $this->getJson('/api/v1/sms/campaigns')->assertOk();
    }

    public function testCreateCampaign()
    {
        $this->postJson('/api/v1/sms/campaigns', [
            'name' => 'Test campaign'
        ])->assertCreated();
    }

    public function testCampaignVars()
    {
        $plan = SmsRoutingPlan::factory()->create([
            'team_id' => $this->user->currentTeam->id,
            'name' => 'Test plan',
        ]);
        $contacts = Contact::factory()->saveAndReturn($this->user->current_team_id);
        $campaign = SmsCampaign::factory()->state(['team_id' => $this->user->currentTeam->id])->create();
        $this->postJson("/api/v1/sms/campaigns/{$campaign->id}/texts", [
            'text' => 'Test campaign var',
        ])->assertCreated();

        $this->getJson("/api/v1/sms/campaigns/{$campaign->id}/texts")->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'text',
                    'is_active',
                    'created_at',
                    'updated_at',
                ]
            ]
        ])->assertJsonCount(1, 'data.*')->assertOk();

        $this->postJson("/api/v1/sms/campaigns/{$campaign->id}/senderids", [
            'text' => 'abcydu',
        ])->assertCreated();
        $this->postJson("/api/v1/sms/campaigns/{$campaign->id}/senderids", [
            'text' => 'abcyasfwrduaisudhiuh',
        ])->assertUnprocessable();

        $this->getJson("/api/v1/sms/campaigns/{$campaign->id}/senderids")->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'text',
                    'is_active',
                    'created_at',
                    'updated_at',
                ]
            ]
        ])->assertJsonCount(1, 'data.*')->assertOk();

        $offer = Offer::factory()->state([
            'team_id' => $this->user->currentTeam->id,
        ])->create();

        $this->postJson("/api/v1/sms/campaigns/{$campaign->id}/offers", [
            'offer_id' => $offer->id,
        ])->assertCreated();

        $this->getJson("/api/v1/sms/campaigns/{$campaign->id}/offers")
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'offer_id',
                        'is_active',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ])->assertJsonCount(1, 'data.*')->assertOk();

        $this->putJson("/api/v1/sms/campaigns/{$campaign->id}", [
            'name' => 'Test campaign 2',
            'meta.send_time' => '00:00',
            'meta.send_date' => '2021-01-01',
            'meta.send_amount' => '100',
        ])->assertOk();

        $this->postJson("/api/v1/sms/campaigns/{$campaign->id}/send-manual")->assertOk();
    }

    public function testSendManualCampaignApi()
    {
        $plan = SmsRoutingPlan::factory()->create([
            'team_id' => $this->user->currentTeam->id,
            'name' => 'Test plan',
        ]);
        BalanceService::addBalance($this->user->current_team_id, 1000, []);
        $route1 = SmsRoute::factory()->withRouteRates()->withSmppConnection()->create([
            'team_id' => $this->user->currentTeam->id,
        ]);
        $route2 = SmsRoute::factory()->withRouteRates()->withSmppConnection()->create([
            'team_id' => $this->user->currentTeam->id,
        ]);
        $contacts = Contact::factory()->saveAndReturn($this->user->current_team_id);
        $campaign = SmsCampaign::factory()->state(['team_id' => $this->user->currentTeam->id])->create();
        $this->postJson("/api/v1/sms/campaigns/{$campaign->id}/texts", [
            'text' => 'Test campaign var',
        ])->assertCreated();

        $this->postJson("/api/v1/sms/campaigns/{$campaign->id}/senderids", [
            'text' => 'abcydu',
        ])->assertCreated();
        $this->postJson("/api/v1/sms/campaigns/{$campaign->id}/senderids", [
            'text' => 'abcyasfwrduaisudhiuh',
        ])->assertUnprocessable();

        $offer = Offer::factory()->state([
            'team_id' => $this->user->currentTeam->id,
        ])->create();

        $this->postJson("/api/v1/sms/campaigns/{$campaign->id}/offers", [
            'offer_id' => $offer->id,
        ])->assertCreated();

        $this->putJson("/api/v1/sms/campaigns/{$campaign->id}", [
            'name' => 'Test campaign 2',
            'meta.send_time' => '00:00',
            'meta.send_date' => '2021-01-01',
            'meta.send_amount' => '100',
        ])->assertOk();

        $this->postJson("/api/v1/sms/campaigns/{$campaign->id}/send-manual", [
            'sms_routing_plan_id' => $plan->id,
        ])->assertOk();
    }
}
