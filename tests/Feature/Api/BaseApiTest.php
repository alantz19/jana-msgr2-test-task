<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Auth\RequestGuard;
use Tests\TestCase;

class BaseApiTest extends TestCase
{
    public static ?User $user;
    public static ?User $initialUser;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$user = null;
        self::$initialUser = null;
    }

    public function actingAsGuest(): void
    {
        $this->app['auth']->logout();
    }

    public function actingAsInitialUser(): void
    {
        self::$user = self::$initialUser;
        $this->actingAs(self::$user);
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (empty(self::$user)) {
            self::$user = User::factory()
                ->withPersonalTeam()
                ->withSanctumToken()
                ->create();
        }

        if (empty(self::$initialUser)) {
            self::$initialUser = self::$user;
        }

        $this->actingAs(self::$user);
        RequestGuard::macro('logout', function () {
            $this->user = null;
        });
    }
}