<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Clickhouse\Contact
 */
class ContactResource extends JsonResource
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
            'foreign_id' => $this->foreign_id,
            'phone' => $this->phone_normalized,
            'phone_is_good' => $this->phone_is_good,
            'email_normalized' => $this->email_normalized,
            'email_is_good' => $this->email_is_good,
            'last_sent_at' => $this->last_sent,
            'last_clicked_at' => $this->last_clicked,
            'sent_count' => $this->sent_count,
            'clicked_count' => $this->clicked_count,
            'leads_count' => $this->leads_count,
            'network_brand' => $this->network_brand,
            'network_id' => $this->network_id,
            'name' => $this->name,
            // @TODO: Add other fields
        ];
    }
}
