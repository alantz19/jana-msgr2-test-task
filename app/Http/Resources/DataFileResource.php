<?php

namespace App\Http\Resources;

use App\Enums\DataFileStatusEnum;
use App\Enums\DataFileTypeEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => DataFileTypeEnum::from($this->type)->label,
            'name' => $this->name,
            'size' => (int) $this->size,
            'status' => DataFileStatusEnum::from($this->status_id)->label,
            'created_at' => $this->created_at->toDateTimeString(),
            'created_ago' => Carbon::parse($this->created_at)->diffForHumans(),
            'columns' => $this->getColumns(),
        ];
    }
}
