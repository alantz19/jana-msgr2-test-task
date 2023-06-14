<?php

namespace App\Http\Resources;

use App\Http\Controllers\SmsRouteCompaniesController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\SmsRouteCompany */
class SmsRouteCompaniesCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'links' => [
                'create' => [
                    'url' => action([SmsRouteCompaniesController::class, 'create']),
                    'label' => 'New Company',
                ],
            ],
        ];
    }
}
