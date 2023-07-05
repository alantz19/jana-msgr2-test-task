<?php

namespace App\Http\Controllers;

use App\Data\SmsRoutingPlanRuleSplitActionVarsData;
use App\Data\SmsRoutingPlanSelectorData;
use App\Enums\SmsRoutingPlanRuleActionEnum;
use App\Http\Resources\SmsRoutingPlanRuleResource;
use App\Models\SmsRoutingPlan;
use App\Models\SmsRoutingPlanRule;
use App\Services\AuthService;
use App\Services\SendingProcess\Routing\SmsRoutingPlanSelectorService;
use Illuminate\Http\Request;

class SmsRoutingPlanRulesController extends Controller
{
    /**
     * @param string $plan
     */
    public function index(SmsRoutingPlan $plan)
    {
        AuthService::isModelOwner($plan);
        $rules = SmsRoutingPlanRule::where(['sms_routing_plan_id' => $plan->id])->get();

        return SmsRoutingPlanRuleResource::collection($rules);
    }

    /**
     * @param string $plan
     */
    public function store(Request $request, SmsRoutingPlan $plan)
    {
        AuthService::isModelOwner($plan);
        $validated = $request->validate([
            'sms_route_id' => 'sometimes|uuid|exists:sms_routes,id',
            'country_id' => 'sometimes|integer|exists:countries,id',
            'network_id' => 'sometimes|integer|exists:networks,id',
            'is_active' => 'sometimes|boolean',
            'priority' => 'sometimes|integer',
            // To create a split rule please check POST ./rules/split endpoint.
            'action' => ['required', 'in:send,drop,filter'],
        ]);

        $rule = SmsRoutingPlanRule::make($validated);
        $rule->sms_routing_plan_id = $plan->id;
        $rule->save();

        return response()->json(new SmsRoutingPlanRuleResource($rule), 201);
    }

    public function storeSplitRule(Request $request, SmsRoutingPlan $plan)
    {
        $validated = $request->validate([
            'country_id' => 'sometimes|integer|exists:countries,id',
            'network_id' => 'sometimes|integer|exists:networks,id',
            'is_active' => 'sometimes|boolean',
            'priority' => 'sometimes|integer',
            ...SmsRoutingPlanRuleSplitActionVarsData::getValidationRules([])
        ]);
        AuthService::isModelOwner($plan);

        $rule = SmsRoutingPlanRule::make($validated);
        $rule->action = SmsRoutingPlanRuleActionEnum::split();
        $rule->sms_routing_plan_id = $plan->id;
        $rule->action_vars = SmsRoutingPlanRuleSplitActionVarsData::from($request->all())->toJson();
        $rule->save();

        return response()->json(new SmsRoutingPlanRuleResource($rule), 201);
    }

    public function patchSplitRule(Request $request, SmsRoutingPlan $plan, SmsRoutingPlanRule $rule)
    {
        $validated = $request->validate([
            'country_id' => 'sometimes|integer|exists:countries,id',
            'network_id' => 'sometimes|integer|exists:networks,id',
            'is_active' => 'sometimes|boolean',
            'priority' => 'sometimes|integer',
            ...SmsRoutingPlanRuleSplitActionVarsData::getValidationRules([])
        ]);
        AuthService::isModelOwner($plan);
        if ($rule->sms_routing_plan_id !== $plan->id) {
            abort(404);
        }

        $rule->fill($validated);
        $rule->action_vars = SmsRoutingPlanRuleSplitActionVarsData::from($request->all())->toJson();
        $rule->save();

        return response()->json(new SmsRoutingPlanRuleResource($rule), 200);
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
