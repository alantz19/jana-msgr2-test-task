<?php

namespace Tests\Browser;

use App\Models\User;
use Faker\Factory;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SignupTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testSignupFail(): void
    {
//        $session = $this->get('/signup')
//            ->assertSee('Signup')
//        ;
        $this->browse(function (Browser $browser) {
            $browser->visit('/signup')
                ->waitFor('input#email')
                ->screenshot('Auth/SignupPage')
                ->press('Signup')
                ->waitForText("The name field is required.")
            ->assertSee('The name field is required.')
            ->assertSee('The email field is required.')
            ->assertSee('The password field is required.')
            ->assertSee('The website field is required')
            ->screenshot('Auth/signup-fail');
        });
    }

    public function testSignupSuccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/signup')
                ->waitFor('input#email')
                ->type('#name', 'Test User')
                ->type('#email', Factory::create()->email)
                ->type('#password', 'password1947')
                ->type('#company_name', Factory::create()->company())
                ->type('#website', Factory::create()->url)
                ->check('#tc_confirm')
                ->press('Signup')
                ->waitForLocation("/")
                ->screenshot('Auth/signup-success');
        });
    }
}
