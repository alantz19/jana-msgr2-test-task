<?php

namespace App\Http\Controllers;

use App\Data\SmppConnectionData;
use App\Data\SmsRoutingRouteCreateData;
use App\Models\SmsRoute;
use App\Models\SmsRouteCompany;
use App\Services\SmppService;
use Inertia\Inertia;

class SmsRoutingRoutesControllesController extends Controller
{
    public function index()
    {
        return Inertia::render('Routing/Routes/Index', [
            'routes' => SmsRoute::where([
                'team_id' => auth()->user()->currentTeam->id
            ])->with(['smsCompany', 'connection'])->get()->only(['name', 'sms_route_company_id', 'connection_id',
                'created_at']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Routing/Routes/Create', [
            'routeCompanies' => SmsRouteCompany::where([
                'team_id' => auth()->user()->currentTeam->id,
            ])->get()->only(['id', 'name']),
            'smsRoutingRouteCreateData' => SmsRoutingRouteCreateData::empty(),
        ]);
    }

    public function store(SmsRoutingRouteCreateData $data)
    {
        dd($data);
    }

    public function testSmppConnection(SmppConnectionData $data)
    {
        if (!SmppService::testConnection($data)) {
            return [
                'success' => false,
                'message' => 'Could not connect to SMPP server.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Successfully connected to SMPP server.',
        ];
    }
}
