<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmsCampaignTextCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'integer'],
            'text' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
