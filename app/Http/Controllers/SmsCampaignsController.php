<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmsCampaignUpdateRequest;
use App\Http\Resources\SmsCampaignResource;
use App\Models\SmsCampaign;
use App\Services\AuthService;
use App\Services\SendCampaignService;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Inertia\Inertia;

class SmsCampaignsController extends Controller
{
    public function index()
    {
        return SmsCampaignResource::collection(SmsCampaign::where(['team_id' => auth()->user()->currentTeam->id])
            ->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $smsCampaign = SmsCampaign::make([
            'name' => $request->name,
        ]);

        $smsCampaign->team_id = auth()->user()->currentTeam->id;
        $smsCampaign->save();

        return response(new SmsCampaignResource($smsCampaign), 201);
    }

    public function update(SmsCampaignUpdateRequest $request, SmsCampaign $smsCampaign)
    {
        if ($request->input('name')) {
            $smsCampaign->name = $request->input('name');
        }
        if ($request->input('meta')) {
            $smsCampaign->addMetaArray($request->input('meta'));
        }

        $smsCampaign->save();
        return response()->json(new SmsCampaignResource($smsCampaign), 200);
    }

    public function sendManual(SmsCampaign $campaign, Request $request)
    {
        AuthService::isModelOwner($campaign);
        $request->validate([
            'sms_amount' => 'sometimes|integer|min:1',
        ]);
        if ($request->sms_amount) {
            $campaign->setMeta('sms_amount', $request->sms_amount);
        }
        $campaign->save();
        SendCampaignService::send($campaign);
        return response()->json(new SmsCampaignResource($campaign), 200);
    }
}
