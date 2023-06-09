<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\RequiredIf;
use Spatie\LaravelData\Data;

/** @typescript */
class SmsRoutingRouteCreateData extends Data
{
    public function __construct(
        #[RequiredIf('selectedCompanyOption', 'new')]
        public ?SmsRoutingCompanyCreateData $companyCreateData,
        #[RequiredIf('selectedCompanyOption', 'existing')]
        public ?int                         $selectedCompanyId,
        #[In(['new', 'existing'])]
        public string                       $selectedCompanyOption = 'new',
    )
    {
    }

    public static function messages()
    {
        return [
            'companyCreateData.name' => 'The company name is required when creating a new company.',
        ];
    }
}
