<?php

namespace Tests\Feature\Api;

use App\Models\SmsRoute;
use App\Models\SmsRouteRate;
use App\Models\User;
use App\Services\CountryService;
use Tests\TestCase;

class ApiRoutingRatesTest extends TestCase
{
    public function testAddRouteRate()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $route = SmsRoute::factory()->withSmppConnection()->state([
            'team_id' => $user->current_team_id
        ])->create();

        $this->actingAs($user);
        $this->postJson('/api/v1/sms/routing/rates', [
            'rate' => 0.01,
            'country_id' => CountryService::guessCountry('US'),
            'sms_route_id' => $route->id,
        ])->assertCreated();
    }

    public function testRouteRateIndex()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $route = SmsRoute::factory()->withSmppConnection()->state([
            'team_id' => $user->current_team_id
        ])->create();

        SmsRouteRate::factory()->state([
            'sms_route_id' => $route->id,
            'world_country_id' => CountryService::guessCountry('US'),
        ])->create();

        $this->actingAs($user);
        $this->getJson('/api/v1/sms/routing/rates')
            ->assertOk()->assertJsonStructure([
                'data' => [
                    '*' => [
                        'country_id',
                        'rate',
                        'sms_route_id',
                        'sms_route'
                    ]
                ]
            ]);
    }

    public function testRouteRateLog()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $route = SmsRoute::factory()->withSmppConnection()->state([
            'team_id' => $user->current_team_id
        ])->create();

        $this->actingAs($user);
        $this->postJson('/api/v1/sms/routing/rates', [
            'rate' => 0.01,
            'country_id' => CountryService::guessCountry('US'),
            'sms_route_id' => $route->id,
        ])->assertCreated();

        $this->getJson('/api/v1/sms/routing/rates/logs')
            ->assertOk()->assertJsonStructure([
                'data' => [
                    '*' => [
                        'created_at',
                        'created_by',
                        'action',
                        'country_id',
                        'new_rate',
                        'old_rate',
                        'sms_route_id',
                        'sms_route'
                    ]
                ]
            ]);
    }


}
