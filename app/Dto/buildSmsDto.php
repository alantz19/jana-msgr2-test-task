<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class buildSmsDto extends Data
{
    public function __construct(
        public string $phone_normalized,
        public string $name,
        public string $list_id,
        public string $campaign_id,
        public int $counter,
    )
    {
    }
}
