<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\RequiredIf;
use Spatie\LaravelData\Data;

/** @typescript */
class SmsRoutingRouteCreateData extends Data
{
    public function __construct(
        public string                       $name,
        public ?string                      $description,
        public string                       $selectedCompanyOption,

        #[RequiredIf('selectedCompanyOption', 'new')]
        public ?SmsRoutingCompanyCreateData $companyCreateData,
        #[RequiredIf('selectedCompanyOption', 'existing')]
        public ?string                      $selectedCompanyId,
        #[In(['new', 'existing'])]
        public ?SmppConnectionData          $smppConnectionData,
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
