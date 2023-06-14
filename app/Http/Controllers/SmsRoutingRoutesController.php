<?php

namespace App\Http\Controllers;

use acidjazz\metapi\MetApi;
use App\Data\FlashData;
use App\Data\SmppConnectionData;
use App\Data\SmsRouteViewData;
use App\Data\SmsRoutingRouteCreateData;
use App\Data\SmsRoutingRouteViewData;
use App\Http\Resources\SmsRouteCompaniesCollection;
use App\Http\Resources\SmsRoutingRouteCollection;
use App\Models\SmsRoute;
use App\Models\SmsRouteCompany;
use App\Models\SmsRouteSmppConnection;
use App\Services\SmppService;
use Inertia\Inertia;

class SmsRoutingRoutesController extends Controller
{
    use MetApi;

    /**
     * Display a listing of the resource.
     *
     * @return SmsRoutingRouteCollection
     */
    public function index()
    {
        return $this->render(new SmsRoutingRouteCollection
            (
                SmsRoute::where('team_id',
                    auth()->user()->currentTeam->id)
                    ->with(['smsRouteCompany', 'connection'])
                    ->get()
            )
        );

        return Inertia::render('Routing/Routes/Index', [
            'routes' => new SmsRoutingRouteCollection
            (
                SmsRoute::where('team_id',
                    auth()->user()->currentTeam->id)
                    ->with(['smsRouteCompany', 'connection'])
                    ->get()
            )
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
        $smppConnection = SmsRouteSmppConnection::create($data->smppConnectionData->toArray());

        $route->fill($data->toArray());
        $route->smsRouteCompany()->associate($company);
        $route->smppConnection()->associate($smppConnection);
        $route->save();

        return redirect()
            ->route('sms.routing.routes.index')
            ->with('flash', FlashData::from(['type' => 'success', 'title' => 'Route created.']));
    }

    public function create()
    {
        return Inertia::render('Routing/Routes/Create', [
            'routeCompanies' => new SmsRouteCompaniesCollection(SmsRouteCompany::where([
                'team_id' => auth()->user()->currentTeam->id,
            ])->get()),
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

    public function destroy()
    {
        $route = SmsRoute::findOrFail(request()->route('route'));
        $route->delete();

        return redirect()
            ->route('sms.routing.routes.index')
            ->with('flash', FlashData::from(['type' => 'success', 'title' => 'Route deleted.']));
    }
}
