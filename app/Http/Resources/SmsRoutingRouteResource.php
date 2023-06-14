<?php

namespace App\Http\Resources;

use App\Http\Controllers\SmsRoutingRoutesController;
use App\Models\SmsRouteSmppConnection;
use App\Traits\WhenMorphToLoaded;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmsRoutingRouteResource extends JsonResource
{
    use WhenMorphToLoaded;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'company' => new SmsRouteCompanyResource($this->whenLoaded('smsRouteCompany')),
            'connection' => $this->whenMorphToLoaded('connection', [
                SmsRouteSmppConnection::class => SmsRouteSmppConnectionResource::class,
            ]),
            'created_at' => $this->created_at,
            'links' => [
//                "edit" => action([SmsRoutingRoutesController::class, 'edit'], $this),
//                "update" => action([SmsRoutingRoutesController::class, 'update'], $this),
                "delete" => action([SmsRoutingRoutesController::class, 'destroy'], $this),
            ],
        ];
    }
}
