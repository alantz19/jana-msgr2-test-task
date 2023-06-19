<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use Config;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    public function testLoginSuccess()
    {
        $user = User::factory()->state([
            'password' => bcrypt('password'),
        ])->withPersonalTeam()->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->waitForText('Welcome back!')
                ->type('#email', $user->email)
                ->type('#password', 'password')
                ->screenshot('Auth/LoginPage')
                ->press('Login')
                ->waitForLocation('/')
                ->assertSee('Welcome to comm.com');
        });
    }

    public function testLoginFailEmpty()
    {
        $this->browse(function(Browser $browser){
            $browser->visit('/login')
                ->press('Login')
                ->waitForText('The email field is required.')
                ->assertSee('Password is required')
                ->screenshot('Auth/LoginFailEmpty');
        });
    }

    public function testLoginFailAuth()
    {
        $this->browse(function(Browser $browser){
            $browser->visit('/login')
                ->type('#email', 'test@test.com')
                ->type('#password', 'password')
                ->press('Login')
                ->waitForText('These credentials do not match our records.')
                ->screenshot('Auth/LoginFailAuth');
        });
    }
}
