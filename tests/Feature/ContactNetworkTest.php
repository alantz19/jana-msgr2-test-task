<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Lists;
use App\Models\User;
use Database\Factories\ContactFactory;
use Tests\TestCase;

class ContactNetworkTest extends TestCase
{
    public function testSaveContactsWithNetwork()
    {
        $user = User::factory()->withPersonalTeam()->create();
//        $user = User::factory()->make();
        $list = Lists::factory()->create([
            'team_id' => $user->currentTeam->id,
        ]);
        Contact::factory()->saveAndReturn();
        $contacts = Contact::factory()->saveAndReturn($list->id, 'au', true);
        dd($contacts);
    }
}
