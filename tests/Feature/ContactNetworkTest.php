<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Lists;
use App\Models\User;
use App\Services\SmsContactMobileNetworksService;
use Database\Factories\ContactFactory;
use Tests\TestCase;

class ContactNetworkTest extends TestCase
{
    public function testSaveContactsWithNetwork()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $list = Lists::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        $contacts = Contact::factory()->saveAndReturn($list->id, 'au', true);
        sleep(3);
        $res = SmsContactMobileNetworksService::getNetworksCountByList($list->id);
        foreach ($res as $network){
            $this->assertNotEmpty($network['network_brand'], 'Network brand is empty - ' . json_encode($network));
        }
        dd($res);
    }
}