<?php

namespace App\Services;

use App\Models\SmsCampaignSend;
use ClickHouseDB\Client;

class SmsCampaignContactService
{

    public static function getContacts(SmsCampaignSend $campaignSend) : array
    {
        if ($campaignSend->isAutosender()) {
            return self::getContactsForAutosender($campaignSend);
        }
        //todo: check if contact unsubscribed, add cache
        $limit = $campaignSend->getLimit();
        $lists = $campaignSend->getLists();
//        $microSegments = $campaignSend->getMicroSegments();


        //add chunking mechanism


        return \DB::connection('clickhouse')->select("
        SELECT 
            phone_normalized,
            name,
            list_id
        from contacts
        where
            list_id in (:lists)
        LIMIT :limit
        ", ['lists' => $lists, 'limit' => $limit]);

    }

    private static function getContactsForAutosender(SmsCampaignSend $campaignSend)
    {
        
    }
}
