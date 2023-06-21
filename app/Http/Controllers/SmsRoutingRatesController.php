<?php

namespace App\Http\Controllers;

use App\Enums\SmsRoutingLogActionsEnum;
use App\Http\Resources\SmsRoutingLogsCollection;
use App\Http\Resources\SmsRoutingRateResource;
use App\Http\Resources\SmsRoutingRatesCollection;
use App\Http\Resources\SmsRoutingRouteResource;
use App\Http\Resources\SmsRoutingRoutesCollection;
use App\Models\SmsRoute;
use App\Models\SmsRouteRate;
use App\Models\SmsRoutingLogs;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Response;

class SmsRoutingRatesController extends Controller
{
    public function index()
    {
        return new SmsRoutingRatesCollection(
            auth()->user()->currentTeam->smsRouteRates()->get()
        );
    }

    public function store(Request $request)
    {
        $params = $request->validate([
            'rate' => 'required|numeric',
            'country_id' => 'required|exists:world_countries,id',
            'sms_route_id' => 'required|exists:sms_routes,id',
            'network_id' => 'exists:world_mobile_networks,id',
        ]);

        AuthService::isModelOwner(SmsRoute::where(['id' => $params['sms_route_id']])->first());

        SmsRoutingLogs::create([
            'action' => SmsRoutingLogActionsEnum::new_rate(),
            'sms_route_id' => $params['sms_route_id'],
            'world_country_id' => $params['country_id'],
            'old_rate' => 0,
            'network_id' => $params['network_id'] ?? null,
            'new_rate' => $params['rate'],
            'team_id' => auth()->user()->currentTeam->id,
            'user_id' => auth()->user()->id,
        ]);

        return Response::json(SmsRoutingRateResource::make(SmsRouteRate::make($params)), '201');
    }

    public function logs()
    {
        return new SmsRoutingLogsCollection(
            SmsRoutingLogs::where(['team_id' => auth()->user()->currentTeam->id])->get()
        );
    }
}
