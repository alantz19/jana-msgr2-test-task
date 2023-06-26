<?php

namespace App\Services;

use App\Models\SmsCampaignSend;
use ClickHouseDB\Client;
use DB;

class SmsCampaignContactService
{

    public static function getContacts(SmsCampaignSend $campaignSend): array
    {
//        if ($campaignSend->isAutosender()) {
//            return self::getContactsForAutosender($campaignSend);
//        }
        //todo: check if contact unsubscribed. add - tags, segments, cache
        $limit = $campaignSend->getLimit();
//        $lists = $campaignSend->getLists();
//        $microSegments = $campaignSend->getMicroSegments();


        //add chunking mechanism


        return DB::connection('clickhouse')->select("
        SELECT 
            id as contact_id,
            phone_normalized,
            country_id
        from contacts
        where team_id = :team_id
        LIMIT :limit
        ",
            ['limit' => $limit, 'team_id' => $campaignSend->campaign->team_id]);

    }

    private static function getContactsForAutosender(SmsCampaignSend $campaignSend)
    {

    }
}
