<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmsCampaignTextCreateRequest;
use App\Models\SmsCampaignText;
use Illuminate\Http\Request;

class SmsCampaignTextsController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
    }

    public function store(SmsCampaignTextCreateRequest $request)
    {
        SmsCampaignText::create($request->validated());

        return redirect()->route('sms-campaigns.index');
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
