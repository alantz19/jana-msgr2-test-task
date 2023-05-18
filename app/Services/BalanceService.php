<?php

namespace App\Services;

use App\Models\Balance;
use App\Models\User;
use ClickHouseDB\Client;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    public static function addBalance(User $user, float $amount)
    {
        $balance = Balance::create([
            'id' => \Str::uuid(),
            'user_id' => $user->id,
            'team_id' => $user->current_team_id,
            'amount' => $amount,
        ]);
        if (!$balance->id) {
            throw new \Exception('Balance not created');
        }

        return true;
    }

    public static function getTeamBalance(
        User $user
    ) {
        $db = ClickhouseService::getClient();
        return $db
            ->select('SELECT sum(balance) as balance FROM balances_teams_v where team_id = :team',
            ['team' => $user->current_team_id])->fetchOne('balance');
    }

    public static function deductBalance(
        User $user,
        int $amount
    ) {
        if ($amount > 0) {
            $amount = -$amount;
        }

        $balance = Balance::create([
            'id' => \Str::uuid(),
            'user_id' => $user->id,
            'team_id' => $user->current_team_id,
            'amount' => $amount,
        ]);
        if (!$balance->id) {
            throw new \Exception('Balance not created');
        }

        return true;
    }
}
