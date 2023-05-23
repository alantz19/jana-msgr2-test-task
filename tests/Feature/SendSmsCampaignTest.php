<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Lists;
use App\Models\Offer;
use App\Models\SmsCampaign;
use App\Models\SmsCampaignSenderid;
use App\Models\SmsCampaignText;
use App\Models\SmsRoute;
use App\Models\SmsRouteCompany;
use App\Models\SmsRoutePlatformConnection;
use App\Models\SmsRouteRate;
use App\Models\SmsRouteSmppConnection;
use App\Models\SmsRoutingPlan;
use App\Models\SmsRoutingPlanRoutes;
use App\Models\User;
use App\Services\CountryService;
use App\Services\SendCampaignService;
use App\Services\UserRoutesService;
use Database\Factories\ContactFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SendSmsCampaignTest extends TestCase
{
    public $user;
//    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        $this->user = $user;

    }

    public function test_send_campaign()
    {
        $routeProvider = User::factory()->withPersonalTeam()->create();

        //setup contacts
        $list = Lists::create([
            'team_id' => $this->user->currentTeam->id,
            'name' => 'Test List',
        ]);

        $contact = Contact::make([
            'team_id' => $this->user->currentTeam->id,
            'list_id' => $list->id,
            'country_id' => CountryService::guessCountry('UK'),
            'phone_normalized' => '447'.rand(10000000, 99999999),
            'phone_is_good' => true,
        ]);
        $contact->save();

        //setup routes
        $plan = SmsRoutingPlan::create([
            'team_id' => $routeProvider->currentTeam->id,
            'name' => 'Test Plan',
        ]);

        $company = SmsRouteCompany::create([
            'team_id' => $routeProvider->currentTeam->id,
            'name' => 'Test Company',
        ]);

        $route = SmsRoute::create([
            'team_id' => $routeProvider->currentTeam->id,
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

        SmsRoutingPlanRoutes::create([
            'routing_plan_id' => $plan->id,
            'sms_route_id' => $route->id,
        ]);


        /** @var SmsRoutePlatformConnection $connection */
        $connection = SmsRoutePlatformConnection::create([
            'plan_id' => $plan->id,
            'name' => 'SMSEdge',
            'customer_team_id' => $this->user->current_team_id,
            'rate_multiplier' => 1.1,
        ]);
        $this->user->currentTeam->smsRoutePlatformConnections()->attach($connection->id);

        //setup campaign
        $campaign = SmsCampaign::factory()->create([
            'team_id' => $this->user->currentTeam->id,
        ]);

        $campaign->setLists([$list->id]);

        SmsCampaignText::factory()->count(5)->create([
            'campaign_id' => $campaign->id,
        ]);

        SmsCampaignSenderId::factory()->count(5)->create([
            'campaign_id' => $campaign->id,
        ]);

        Offer::factory()->count(5)->create([
            'team_id' => $this->user->currentTeam->id,
        ])->each(function($model) use ($campaign) {
            $campaign->offers()->attach($model->id);
        });
        $campaign->routes()->attach($route->id);

        $campaign->setSettings([
            'send_time' => null,
            'sms_amount' => 100
        ]);

        SendCampaignService::send($campaign);
    }
}
