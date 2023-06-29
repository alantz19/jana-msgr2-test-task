<?php

namespace App\Http\Requests;

use App\Models\SmsCampaign;
use App\Services\AuthService;
use Illuminate\Foundation\Http\FormRequest;

class SmsCampaignUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'min:3', 'max:255'],

            //H:i - 24 hour format (f.e - 13:00)
            'meta.send_time' => ['sometimes', 'date_format:H:i'],
            //Y-m-d - date format (f.e - 2021-01-01)
            'meta.send_date' => ['sometimes', 'date_format:Y-m-d'],
            'meta.sms_amount' => ['sometimes', 'integer', 'min:1'],
            'meta.sms_routing_plan_id' => ['sometimes', 'uuid', 'exists:sms_routing_plans,id'],
        ];
    }

    public function authorize(): bool
    {
        $campaign = SmsCampaign::where(['id' => $this->route()->parameter('campaign')])->first();
        return AuthService::isModelOwner($campaign);
    }
}
