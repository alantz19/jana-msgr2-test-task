<?php

namespace App\Services;

use App\Models\SmsRoute;
use App\Models\SmsRoutePlatformConnection;
use App\Models\SmsRoutingPlan;
use App\Models\Team;
use App\Models\User;

class PlatformRoutesService
{
    public static function getCustomersForSeller(User $user)
    {
        return $user->currentTeam->routingPlanConnections;
    }
}
