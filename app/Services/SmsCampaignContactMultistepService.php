<?php

namespace App\Services;

use App\Data\CampaignMultistepStatusData;
use App\Enums\SmsCampaignStatusEnum;
use App\Models\Domain;
use App\Models\Segment;
use App\Models\SmsCampaignSend;
use Carbon\Carbon;
use ClickHouseDB\Client;
use ClickHouseDB\Query\Query;
use Database\Factories\DomainFactory;
use DB;
use Log;
use Tinderbox\ClickhouseBuilder\Query\TwoElementsLogicExpression;

class SmsCampaignContactMultistepService extends SmsCampaignContactService
{
    protected static function getContactsForMultistepCampaign(SmsCampaignSend $campaignSend): array
    {
        $settings = $campaignSend->getMultistepSettings();
        Log::debug("multistep settings", ['settings' => $settings]);

        $status = $campaignSend->getMultistepStatus();
        Log::debug('getContactsForMultistepCampaign', ['status' => $status]);


        if ($status->current_step === 0) {
            return self::handleFirstStep($campaignSend);
        } else {
            return self::handleSubsequentSteps($campaignSend);
        }
    }

    private static function handleFirstStep(SmsCampaignSend $campaignSend): array
    {
        $settings = $campaignSend->getMultistepSettings();
        $status = $campaignSend->getMultistepStatus();
        $limit = $settings->step_size;
        $baseQuery = self::getBaseQuery($campaignSend);

        $total_available_contacts = self::getTotalAvailableContacts($campaignSend);
        $status->total_available_contacts = $total_available_contacts;

        if ($total_available_contacts == 0) {
            Log::debug("No contacts found for campaign {$campaignSend->id}");
            //todo: add user notification
            $status->status = SmsCampaignStatusEnum::sent()->value;
            $campaignSend->setMultistepStatus($status);
            return [];
        }

        Log::debug("total_available_contacts: {$total_available_contacts}");
        if ($total_available_contacts < $limit) {
            Log::debug("last step {$total_available_contacts} < {$limit}");
            $status->status = SmsCampaignStatusEnum::sent()->value;
            $campaignSend->status = SmsCampaignStatusEnum::sent()->value;
            $limit = $total_available_contacts;
        }

        $brands = self::getBrandsFromBaseQuery($baseQuery);
        $status->initial_brands = $brands;

        $campaignSend->next_step_timestamp =
            now()->addMinutes($campaignSend->getMultistepSettings()->step_delay)->toDateTime();
        Log::debug("segment network brands", ['brands' => $brands]);
        $status->current_step++;
        $status->last_sent_timestamp = microtime(true);
        $campaignSend->setMultistepStatus($status);

        $baseQuery = self::getBaseQuery($campaignSend);
        $query = "select * from ({$baseQuery}) limit {$limit}";
        Log::debug('CH query', ['query' => $query]);

        $status->total_sent += $limit;
        $status->start_timestamp = microtime(true);
        $status->last_sent_timestamp = microtime(true);
        $campaignSend->setMultistepStatus($status);

        return self::queryContacts($campaignSend, $query, $limit);
    }

    private static function getTotalAvailableContacts(SmsCampaignSend $campaignSend): int
    {
        $baseQuery = self::getBaseQuery($campaignSend);
        $query = "select count(*) as count from ({$baseQuery})";
        $total_available_contacts = ClickhouseService::query($query);
        return $total_available_contacts[0]['count'];
    }

    private static function getBrandsFromBaseQuery(string $baseQuery): array
    {
        $query =
            "select count(*) as count, network_brand from ({$baseQuery}) group by network_brand order by count asc";
        $brands = ClickhouseService::query($query, 'network_brand');
        return collect($brands)->map(function ($item) {
            return $item['count'];
        })->toArray();
    }

    private static function handleSubsequentSteps(SmsCampaignSend $campaignSend): array
    {
        $settings = $campaignSend->getMultistepSettings();
        $status = $campaignSend->getMultistepStatus();
        $limit = $settings->step_size;

        $status = self::createLastStepStats($campaignSend, $status);
        //todo: based on performance disable routes

        $baseQuery = self::getBaseQuery($campaignSend);

        //change to last step
        $firstSent = Carbon::createFromTimestamp($status->start_timestamp)->toDateTimeString();
        $query = "select * from ({$baseQuery}) where (last_sent < '{$firstSent}' OR last_sent IS NULL) limit {$limit}";


        Log::debug('CH query', ['query' => $query]);

        $status->total_sent += $limit;
        $status->last_sent_timestamp = microtime(true);
        $campaignSend->next_step_timestamp =
            now()->addMinutes($campaignSend->getMultistepSettings()->step_delay)->toDateTime();

        $campaignSend->setMultistepStatus($status);
        return self::queryContacts($campaignSend, $query, $limit);
    }

    private static function createLastStepStats(SmsCampaignSend $campaignSend, CampaignMultistepStatusData $status)
    {
        $previousStepTimestamp = Carbon::createFromTimestamp($campaignSend->getMultistepStatus()->last_sent_timestamp)
            ->toDateTimeString();
        Log::debug("previous step timestamp", ['timestamp' => $previousStepTimestamp]);

        //get stats of previous step
        $baseLastStepStats =
            "select * from sms_sendlogs_v where sms_campaign_send_id = '{$campaignSend->id}' and sent_at >= '{$previousStepTimestamp}'";
        Log::debug("previous step stats query", ['query' => $baseLastStepStats]);

        $domainStats = <<<SQL
SELECT domain_id, 
       countIf(is_clicked = 1) AS total_clicks, 
       count() AS total_sent, 
       (countIf(is_clicked = 1) * 1.0 / count()) * 100 AS ctr
FROM ($baseLastStepStats)
WHERE is_sent = 1
GROUP BY domain_id;
SQL;
        $domainStats = ClickhouseService::query($domainStats, 'domain_id');
        //replace domain_id with domain name from Domain table
        $domainStats = collect($domainStats)->map(function ($item, $key) {
//            $domain = Domain::find($item['domain_id']);
            //todo: fix after domain api implementation
            $domain = Domain::factory()->create();
            $item['domain'] = $domain->domain;
            unset($item['domain_id']);
            return $item;
        })->toArray();

        Log::debug("domain stats", ['domainStats' => $domainStats]);

        $senderid_performance = <<<SQL
SELECT sender_id, 
       countIf(is_clicked = 1) AS total_clicks, 
       count() AS total_sent, 
       (countIf(is_clicked = 1) * 1.0 / count()) * 100 AS ctr
FROM ($baseLastStepStats)
WHERE is_sent = 1
GROUP BY sender_id;
SQL;
        $senderid_performance = ClickhouseService::query($senderid_performance, 'sender_id');
        Log::debug("senderid_performance", ['senderid_performance' => $senderid_performance]);

        $route_performance = <<<SQL
SELECT sms_routing_route_id,
         countIf(is_clicked = 1) AS total_clicks,
         count() AS total_sent,
         (countIf(is_clicked = 1) * 1.0 / count()) * 100 AS ctr
FROM ($baseLastStepStats)
WHERE is_sent = 1
GROUP BY sms_routing_route_id;
SQL;

        $route_performance = ClickhouseService::query($route_performance, 'sms_routing_route_id');
        Log::debug("route_performance", ['route_performance' => $route_performance]);

        $campaignTextsPerformance = <<<SQL
SELECT campaign_text_id,
         countIf(is_clicked = 1) AS total_clicks,
         count() AS total_sent,
         (countIf(is_clicked = 1) * 1.0 / count()) * 100 AS ctr
FROM ($baseLastStepStats)
WHERE is_sent = 1
GROUP BY campaign_text_id;
SQL;
        $campaignTextsPerformance = ClickhouseService::query($campaignTextsPerformance, 'campaign_text_id');
        Log::debug("campaignTextsPerformance", ['campaignTextsPerformance' => $campaignTextsPerformance]);

        $offersPerformance = <<<SQL
SELECT offer_id,
         countIf(is_clicked = 1) AS total_clicks,
         count() AS total_sent,
         (countIf(is_clicked = 1) * 1.0 / count()) * 100 AS ctr
FROM ($baseLastStepStats)
WHERE is_sent = 1
GROUP BY offer_id;
SQL;
        $offersPerformance = ClickhouseService::query($offersPerformance, 'offer_id');
        Log::debug("offersPerformance", ['offersPerformance' => $offersPerformance]);

        $status->steps_performance[$status->current_step - 1] = [
            'domain' => $domainStats,
            'senderid' => $senderid_performance,
            'route' => $route_performance,
            'campaign_text' => $campaignTextsPerformance,
            'offers' => $offersPerformance,
        ];

        return $status;
//            $networkBrandStats=<<<SQL
//SELECT network_brand,
//         countIf(is_clicked = 1) AS total_clicks,
//         count() AS total_sent,
//         (countIf(is_clicked = 1) * 1.0 / count()) * 100 AS ctr
//FROM ($baseLastStepStats)
//WHERE is_sent = 1
//GROUP BY network_brand;
//SQL;


    }
}