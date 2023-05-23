<?php

namespace App\Services;

use App\Models\CustomerRoute;
use App\Models\SmsRoute;
use App\Models\SmsRoutePlatformConnection;
use App\Models\SmsRouteSmppConnection;
use App\Models\SmsRoutingPlan;
use App\Models\SmsRoutingPlanRoutes;
use App\Models\User;

class UserRoutesService
{

    public static function getAvailableRoutes(User $user)
    {
        $smppRoutes = SmsRoute::where(['connection_type' => SmsRouteSmppConnection::class])
            ->where(['team_id' => $user->current_team_id])->get();


        $planConnections = SmsRoutePlatformConnection::
        where(['customer_team_id' => $user->current_team_id])
            ->where(['is_active' => true])->get();
        $planRoutes = [];
        if ($planConnections->isNotEmpty()) {
            foreach ($planConnections as $i => $planConnection) {
                $planRoutes[$i]['connection'] = $planConnection;
                $routes = SmsRoutingPlanRoutes::where([
                    'sms_routing_plan_id' => $planConnections->pluck('sms_routing_plan_id')->all()
                ])->get();
                if ($routes->isNotEmpty()) {
                    $planRoutes[$i]['routes'] = CustomerRoute::where(
                        [
                            'id' => $routes->pluck('sms_route_id')->all()
                        ])->get();
                }

            }
        }

        return [
            'private' => $smppRoutes,
            'platform' => $planRoutes
        ];
    }

    public static function getAvailableRoutesForCountry(User $user, $country): array
    {
        $country = CountryService::guessCountry($country);
        $routes = self::getAvailableRoutes($user);
        $prices = [
            'private' => [],
            'platform' => [],
        ];
        foreach ($routes['private'] as $customRoutes) {
            /** @var SmsRoute $customRoutes */
            $rate = $customRoutes->rates()->where(['world_country_id' => $country])->first();
            if ($rate) {
                $customRoutes->priceForCountry = $rate->rate;
                $prices['private'][] = $customRoutes;
            }
        }

        foreach ($routes['platform'] as $planRoutes) {
            foreach ($planRoutes['routes'] as $route){
                if (self::setPlatformRate($route, $country, $planRoutes['connection'])) {
                    $prices['platform'][] = $route;
                }
            }
        }

        return $prices;
    }

    private static function setPlatformRate(CustomerRoute $route, int $country, SmsRoutePlatformConnection $connection)
    {
        $rate = $route->rates()->where(['world_country_id' => $country])->first();
        if ($rate) {
            $route->priceForCountry = $rate->rate * $connection->rate_multiplier;
            $route->platformConnection = $connection;
            return true;
        }
        return false;
    }

}
