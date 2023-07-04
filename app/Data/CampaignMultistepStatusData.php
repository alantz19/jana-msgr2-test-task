<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CampaignMultistepStatusData extends Data
{
    public function __construct(
        public int $current_step = 0,
    )
    {
    }
}
