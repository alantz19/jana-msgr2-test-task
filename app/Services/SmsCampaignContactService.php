<?php

namespace App\Services;

use App\Models\SmsCampaignSend;
use ClickHouseDB\Client;
use DB;
use Log;

class SmsCampaignContactService
{

    public static function getContacts(SmsCampaignSend $campaignSend): array
    {
//        if ($campaignSend->isAutosender()) {
//            return self::getContactsForAutosender($campaignSend);
//        }
        //todo: check if contact unsubscribed. add - tags, segments, cache
        $limit = 100;
//        $limit = $campaignSend->getLimit();
//        $lists = $campaignSend->getLists();
//        $microSegments = $campaignSend->getMicroSegments();


        //add chunking mechanism

        /** @var Client $client */
        $client = DB::connection('clickhouse')->getClient();
        $res = $client->select("
        SELECT 
            id as contact_id,
            name,
            phone_normalized,
            country_id
        from contacts
        where team_id = :team_id
        LIMIT :limit
        ",
            ['limit' => $limit, 'team_id' => $campaignSend->campaign->team_id]);
        Log::debug("queried contacts: " . $res->count(), ['contacts' => array_merge($res->statistics(),
            $res->responseInfo()
        )]);
        return $res->rows();
    }

    private static function getContactsForAutosender(SmsCampaignSend $campaignSend)
    {

    }
}
