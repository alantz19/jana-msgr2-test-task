<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Lists;
use App\Models\Offer;
use App\Models\SmsCampaign;
use App\Models\SmsCampaignSenderid;
use App\Models\SmsCampaignText;
use App\Models\User;
use App\Services\CountryService;
use App\Services\SendCampaignService;
use Tests\TestCase;

class SendSmsCampaignTest extends TestCase
{
    public $user;

    public function test_send_campaign()
    {
        self::markTestSkipped();
        $user = User::factory()->withPersonalTeam()->create();
        $userId = $user->id;

        //setup contacts
        $list = Lists::create([
            'team_id' => $user->currentTeam->id,
            'name' => 'Test List',
        ]);

        $contact = Contact::make([
            'team_id' => $user->currentTeam->id,
            'list_id' => $list->id,
            'country_id' => CountryService::guessCountry('UK'),
            'phone_normalized' => '447' . rand(10000000, 99999999),
            'phone_is_good' => true,
        ]);
        $contact->save();
//        dd($user->current_team_id);
        $seller = User::factory()->asUkRouteSeller($user->currentTeam)->create();

        //setup campaign
        $campaign = SmsCampaign::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);

        $campaign->setLists([$list->id]);

        SmsCampaignText::factory()->count(5)->create([
            'sms_campaign_id' => $campaign->id,
        ]);

        SmsCampaignSenderId::factory()->count(5)->create([
            'sms_campaign_id' => $campaign->id,
        ]);

        Offer::factory()->count(5)->create([
            'team_id' => $user->currentTeam->id,
        ])->each(function ($model) use ($campaign) {
            $campaign->offers()->attach($model->id);
        });

        $campaign->setSettings([
            'send_time' => null,
            'sms_amount' => 100
        ]);

        SendCampaignService::send($campaign);
        $this->assertEquals(true, true); //todo: continue after campagin creator/autosender
    }
}
