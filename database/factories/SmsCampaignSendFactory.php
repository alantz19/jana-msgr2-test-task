<?php

namespace Database\Factories;

use App\Models\SmsCampaign;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsCampaignSendFactory extends Factory
{
    public function definition(): array
    {
        return [
          'sms_campaign_id' => SmsCampaign::factory()->create()->id,
        ];
    }
}
