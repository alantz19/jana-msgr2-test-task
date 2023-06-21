<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\SmsRouteRate */
class SmsRoutingRatesCollection extends ResourceCollection
{
    public $collects = SmsRoutingRateResource::class;

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
