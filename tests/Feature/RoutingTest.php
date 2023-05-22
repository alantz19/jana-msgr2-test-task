<?php

namespace Tests\Feature;

use App\Models\SmsRoute;
use App\Models\SmsRouteCompany;
use App\Models\SmsRouteRate;
use App\Models\SmsRouteSmppConnection;
use App\Models\SmsRoutingPlan;
use App\Models\User;
use App\Services\ClickhouseService;
use App\Services\CountryService;
use Tests\TestCase;

class RoutingTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        $this->user = $user;

    }

    public function testBasic()
    {
        $plan = SmsRoutingPlan::create([
            'team_id' => $this->user->currentTeam->id,
            'name' => 'Test Plan',
        ]);

        $company = SmsRouteCompany::create([
            'team_id' => $this->user->currentTeam->id,
            'name' => 'Test Company',
        ]);

        $route = SmsRoute::create([
            'team_id' => $this->user->currentTeam->id,
            'name' => 'Test Route',
            'routing_plan_id' => $plan->id,
            'route_company_id' => $company->id,
        ]);

        $connection = SmsRouteSmppConnection::create([
            'url' => '167.235.66.91',
            'username' => 'admin',
            'password' => 'admin',
            'port' => 2775,
        ]);
        $route->smppConnections()->associate($connection);
        $route->saveOrFail();

        $rate = SmsRouteRate::create([
            'route_id' => $route->id,
            'world_country_id' => CountryService::guessCountry('UK'),
            'rate' => 0.01,
        ]);


        //get route rate for country
        
        //get available routes for user

    }
}
