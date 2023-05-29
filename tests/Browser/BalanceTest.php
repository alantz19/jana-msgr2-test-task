<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\Models\User;

class BalanceTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testBalanceUrl(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->withPersonalTeam()->create();
            $browser->loginAs($user)
                    ->visit('livewire/message/balance-component')
                    ->assertSee('Laravel');
        });
    }
}
