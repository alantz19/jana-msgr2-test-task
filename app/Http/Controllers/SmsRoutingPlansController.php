<?php

namespace App\Http\Controllers;

use App\Http\Resources\SmsRoutingPlanResource;
use App\Models\SmsRoutingPlan;
use App\Services\AuthService;
use Illuminate\Http\Request;

class SmsRoutingPlansController extends Controller
{
    public function index()
    {
        return SmsRoutingPlanResource::collection(
            SmsRoutingPlan::where(['team_id' => auth()->user()->currentTeam->id])->orderByDesc('created_at')->get()
        );
    }

    public function show(SmsRoutingPlan $plan)
    {
        AuthService::isModelOwner($plan);

        return new SmsRoutingPlanResource($plan);
    }

    public function store(Request $request)
    {
        $plan = SmsRoutingPlan::make(
            $request->validate([
                'name' => 'required|string',
            ])
        );
        $plan->team_id = auth()->user()->currentTeam->id;
        $plan->save();

        return response(new SmsRoutingPlanResource($plan), 201);
    }

    public function update(SmsRoutingPlan $plan, Request $request)
    {
        AuthService::isModelOwner($plan);
        
        $plan->update(
            $request->validate([
                'name' => 'required|string',
            ])
        );

        return new SmsRoutingPlanResource($plan);
    }

    public function destroy(SmsRoutingPlan $plan)
    {
        AuthService::isModelOwner($plan);
        $plan->delete();

        return response(null, 204);
    }
}
