<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/** @typescript */
class SmsRoutingCompanyViewData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
    )
    {
    }
}
