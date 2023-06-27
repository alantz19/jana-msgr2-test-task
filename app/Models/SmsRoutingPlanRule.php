<?php

namespace App\Models;

use App\Enums\SmsRoutingPlanRuleActionEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

/**
 * @property SmsRoutingPlan $plan
 */
class SmsRoutingPlanRule extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    public static array $rules;//
    protected $fillable = [
        'sms_route_id',
        'sms_routing_plan_id',
        'country_id',
        'network_id',
        'is_active',
        'priority',
        'action',
        'action_vars',
    ];

    public static function getRules()
    {
        return [
            'sms_route_id' => 'sometimes|uuid',
            'country_id' => 'sometimes|integer|exists:countries,id',
            'network_id' => 'sometimes|integer|exists:networks,id',
            'is_active' => 'sometimes|boolean',
            'priority' => 'sometimes|integer',
            'action' => ['required', Rule::in(SmsRoutingPlanRuleActionEnum::toArray())],
            'action_vars' => 'nullable|array',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SmsRoutingPlan::class, 'sms_routing_plan_id');
    }
}
