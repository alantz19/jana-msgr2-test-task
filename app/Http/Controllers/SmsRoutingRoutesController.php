<?php

namespace App\Http\Controllers;

use App\Data\FlashData;
use App\Data\SmppConnectionData;
use App\Data\SmsRoutingRouteCreateData;
use App\Models\SmsRoute;
use App\Models\SmsRouteCompany;
use App\Models\SmsRouteSmppConnection;
use App\Services\SmppService;
use Inertia\Inertia;

class SmsRoutingRoutesController extends Controller
{
    public function index()
    {
        return Inertia::render('Routing/Routes/Index', [
            'routes' => SmsRoute::where(
                'team_id',
                auth()->user()->currentTeam->id)
                ->with(['smsCompany', 'connection'])
                ->get()
//                ->only(['name', 'sms_route_company_id', 'connection_id', 'created_at']),
        ]);
    }

    public function store(SmsRoutingRouteCreateData $data)
    {
        $route = new SmsRoute();
        if ($data->smppConnectionData) {
            if (!SmppService::testConnection($data->smppConnectionData)) {
                return redirect()
                    ->back()
                    ->withInput($data->toArray())
                    ->withErrors(['smppConnectionError' => 'Could not connect to SMPP server.']);
            }
        }

        $company = SmsRouteCompany::create($data->companyCreateData->toArray());
        $route->fill($data->toArray());
        $route->sms_route_company_id = $company->id;
        $route->save();
        $smppConnection = SmsRouteSmppConnection::create($data->smppConnectionData->toArray());
        $route->smppConnection()->associate($smppConnection);

        return redirect()
            ->route('sms.routing.routes.index')
            ->with('flash', FlashData::from(['type' => 'success', 'title' => 'Route created.']));
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
