<?php

namespace App\Services;

use App\Models\Domain;
use App\Services\SendingProcess\Data\BuildSmsData;
use Log;
use Ramsey\Uuid\Uuid;

class DomainService
{

    public static function getDomainForCampaign(BuildSmsData $msg)
    {
        Log::debug('getDomainForCampaign');
        $domain = Domain::make([
            'domain' => 'google.com',
            'url' => 'https://www.google.com',
            'is_active' => true,
            'id' => Uuid::uuid4()
        ]);
        $domain->id = Uuid::uuid4();
        $domain->team_id = $msg->sendToBuildSmsData->team_id;
//        $domain->save();
        return $domain;
        //todo
    }
}
