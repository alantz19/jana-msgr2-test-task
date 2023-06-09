<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/** @typescript */
class SmsRoutingCompanyCreateData extends Data
{
    public function __construct(
        public string $name,
    )
    {
    }
}
