<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\SmsRoutingRouteCreateRequest;
use App\Http\Resources\SmsRouteCompanyResource;
use App\Models\SmsRouteCompany;
use Illuminate\Http\Request;

class SmsRouteCompaniesController extends Controller
{
    public function index()
    {

    }

    public function store(SmsRoutingRouteCreateRequest $request)
    {
        return new SmsRouteCompanyResource(SmsRouteCompany::create($request->toArray()));
    }

    public function create()
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
