<?php

namespace Tests\Feature;

use App\Models\SmsRoute;
use App\Models\SmsRouteCompany;
use App\Models\SmsRoutePlatformConnection;
use App\Models\SmsRouteRate;
use App\Models\SmsRouteSmppConnection;
use App\Models\SmsRoutingPlan;
use App\Models\SmsRoutingPlanRoutes;
use App\Models\User;
use App\Services\ClickhouseService;
use App\Services\CountryService;
use App\Services\PlatformRoutesService;
use App\Services\UserRoutesService;
use Tests\TestCase;

class RoutingTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        $this->user = $user;
        $user = User::factory()->withPersonalTeam()->create();
        $this->customer = $user;

    }

    public function testUserCanAddPrivateRoutes()
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
            'sms_route_id' => $route->id,
            'world_country_id' => CountryService::guessCountry('UK'),
            'rate' => 0.01,
        ]);

        //get route rate for country
        SmsRoutingPlanRoutes::create([
            'routing_plan_id' => $plan->id,
            'sms_route_id' => $route->id,
        ]);

        $routes = UserRoutesService::getAvailableRoutes($this->user);
        $this->assertNotEmpty($routes['private']);
        $this->assertEquals($routes['private'][0]->id, $route->id);
        $prices = UserRoutesService::getAvailableRoutesForCountry($this->user, 'UK');
        $this->assertEquals($prices['private'][0]->priceForCountry, 0.01);
    }

    public function testUserCanConnectPlatformRoutes()
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
            'sms_route_id' => $route->id,
            'world_country_id' => CountryService::guessCountry('UK'),
            'rate' => 0.01,
        ]);

        //get route rate for country
        SmsRoutingPlanRoutes::create([
            'routing_plan_id' => $plan->id,
            'sms_route_id' => $route->id,
        ]);

        //get available routes for user
        SmsRoutePlatformConnection::create([
            'plan_id' => $plan->id,
            'name' => 'SMSEdge',
            'customer_team_id' => $this->customer->current_team_id,
            'rate_multiplier' => 1.1,
        ]);

        $routes = UserRoutesService::getAvailableRoutes($this->customer);
        $this->assertEquals($routes['platform'][0]['routes'][0]->id, $route->id);
        $this->assertEquals($routes['platform'][0]['connection']->name, 'SMSEdge');

        $routes = UserRoutesService::getAvailableRoutesForCountry($this->customer, 'UK');
        $this->assertEquals($routes['platform'][0]->id, $route->id);
        $this->assertEquals($routes['platform'][0]->platformConnection->name, 'SMSEdge');
        $this->assertEquals(0.011, round($routes['platform'][0]->priceForCountry*10000)/10000 );
        $this->assertEquals('SMSEdge::Test Route', $routes['platform'][0]->customerRouteName);
    }


    public function testUserCanSeeWhichPlansHeSelling()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $customer = User::factory()->withPersonalTeam()->create();

        $plan = SmsRoutingPlan::create([
            'team_id' => $user->currentTeam->id,
            'name' => 'Test Plan',
        ]);

        $company = SmsRouteCompany::create([
            'team_id' => $user->currentTeam->id,
            'name' => 'Test Company',
        ]);

        $route = SmsRoute::create([
            'team_id' => $user->currentTeam->id,
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
            'sms_route_id' => $route->id,
            'world_country_id' => CountryService::guessCountry('UK'),
            'rate' => 0.01,
        ]);

        //get route rate for country
        SmsRoutingPlanRoutes::create([
            'routing_plan_id' => $plan->id,
            'sms_route_id' => $route->id,
        ]);

        //get available routes for user
        SmsRoutePlatformConnection::create([
            'plan_id' => $plan->id,
            'name' => 'SMSEdge',
            'customer_team_id' => $customer->current_team_id,
            'rate_multiplier' => 1.1,
        ]);


        $res = PlatformRoutesService::getCustomersForSeller($user);
        $this->assertEquals($res[0]->customer_team_id, $customer->current_team_id);
        $this->assertEquals($res[0]->is_active, true);
    }
}
