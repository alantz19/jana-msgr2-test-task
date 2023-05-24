<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\BalanceService;
use Tests\TestCase;

class BalanceTest extends TestCase
{
    public function testBalanceLogic()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->assertEquals(0, BalanceService::getTeamBalance($user));
        $this->assertEquals(true, BalanceService::addBalance($user, 100));
        $this->assertEquals(100, BalanceService::getTeamBalance($user));
        $this->assertEquals(true, BalanceService::deductBalance($user, 50));
        $this->assertEquals(50, BalanceService::getTeamBalance($user));

    }
}
