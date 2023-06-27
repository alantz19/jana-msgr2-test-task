<?php

namespace App\Http\Controllers;

use App\Enums\SmsRoutingPlanRuleActionEnum;
use App\Http\Resources\SmsRoutingPlanResource;
use App\Http\Resources\SmsRoutingPlanRuleResource;
use App\Models\SmsRoutingPlan;
use App\Models\SmsRoutingPlanRule;
use App\Services\AuthService;
use Illuminate\Http\Request;
use PHPStan\Rules\Rule;

class SmsRoutingPlanRulesController extends Controller
{
    public function index(SmsRoutingPlan $plan)
    {
        AuthService::isModelOwner($plan);
        $rules = SmsRoutingPlanRule::where(['sms_routing_plan_id' => $plan->id])->get();

        return SmsRoutingPlanRuleResource::collection($rules);
    }

    public function store(Request $request, SmsRoutingPlan $plan)
    {
        AuthService::isModelOwner($plan);
        $validated = $request->validate(SmsRoutingPlanRule::getRules());

        $rule = SmsRoutingPlanRule::make($validated);
        $rule->sms_routing_plan_id = $plan->id;
        $rule->save();

        return response()->json(new SmsRoutingPlanRuleResource($rule), 201);
    }

    public function update(Request $request, SmsRoutingPlan $plan, SmsRoutingPlanRule $rule)
    {
        AuthService::isModelOwner($plan);
        if ($rule->sms_routing_plan_id !== $plan->id) {
            abort(404);
        }

        $validated = $request->validate(SmsRoutingPlanRule::getRules());
        $rule->update($validated);

        return response()->json(new SmsRoutingPlanRuleResource($rule), 200);
    }

    public function destroy(SmsRoutingPlan $plan, SmsRoutingPlanRule $rule)
    {
        AuthService::isModelOwner($plan);
        if ($rule->sms_routing_plan_id !== $plan->id) {
            abort(404);
        }

        $rule->delete();
        return response()->json(null, 204);
    }
}
