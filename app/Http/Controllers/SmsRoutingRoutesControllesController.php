<?php

namespace App\Http\Controllers;

use App\Models\SmsRoute;
use Inertia\Inertia;

class SmsRoutingRoutesControllesController extends Controller
{
    public function index()
    {
        return Inertia::render('Routing/Routes/Index',[
            'routes' => \App\Models\SmsRoute::where([
                'team_id' => auth()->user()->currentTeam->id
            ])->with(['smsCompany', 'connection'])->get()->only(['name', 'sms_route_company_id', 'connection_id', 'created_at']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Routing/Routes/Create', [
            'routeCompanies' => \App\Models\SmsRouteCompany::where([
                'team_id' => auth()->user()->currentTeam->id
            ])->get()->only(['id', 'name']),
        ]);
    }
}
