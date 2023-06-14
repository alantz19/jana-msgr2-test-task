<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmsRouteSmppConnectionCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required'],
            'username' => ['required'],
            'password' => ['required'],
            'port' => ['integer', 'required'],
            'dlr_url' => ['nullable'],
            'dlr_port' => ['nullable', 'integer'],
            'workers_count' => ['nullable', 'integer'],
            'workers_delay' => ['nullable', 'numeric'],
        ];
    }
}
