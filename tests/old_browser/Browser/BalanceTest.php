<?php

namespace Tests\old_browser\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
