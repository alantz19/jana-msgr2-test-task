<?php

namespace App\Http\Controllers;

use App\Data\SmsRoutingCompanyCreateData;
use App\Models\SmsRouteCompany;
use Inertia\Inertia;

class SmsRouteCompanyController extends Controller
{
    public function index()
    {
      return Inertia::render('Routing/Companies/Index',[
        'companies' => \App\Models\SmsRouteCompany::where(['team_id' => \Auth::user()->current_team_id])->get()
      ]);
    }

    public function create()
    {
        return Inertia::render('Routing/Companies/SmsCompaniesCreate',[
            'company' => SmsRoutingCompanyCreateData::empty(),
        ]);
    }

    public function store(SmsRoutingCompanyCreateData $data)
    {
        $company = SmsRouteCompany::make($data->toArray());
        $company->team_id = \Auth::user()->current_team_id;
        $company->save();

        return redirect()->route('sms.routing.companies.index');
    }

    public function edit(SmsRouteCompany $company)
    {
        if ($company->team_id !== \Auth::user()->current_team_id) {
            abort(403);
        }
        return Inertia::render('Routing/Companies/SmsCompaniesUpdate',[
            'company' => $company->only(['id', 'name']),
        ]);
    }

    public function update(SmsRouteCompany $company, SmsRoutingCompanyCreateData $data)
    {
        if ($company->team_id !== \Auth::user()->current_team_id) {
            abort(403);
        }

        $company->update($data->toArray());
        return redirect()->route('sms.routing.companies.index');
    }
}
