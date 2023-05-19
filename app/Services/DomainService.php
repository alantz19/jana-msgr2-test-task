<?php

namespace App\Services;

use App\Models\Domain;

class DomainService
{

    public static function getDomainForCampaign(SendingProcess\Data\BuildSmsData $msg)
    {
        return Domain::factory()->create();
        //todo
    }
}
