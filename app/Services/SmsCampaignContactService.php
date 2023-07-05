<?php

namespace App\Services;

use App\Enums\SmsCampaignStatusEnum;
use App\Models\Segment;
use App\Models\SmsCampaignSend;
use ClickHouseDB\Client;
use ClickHouseDB\Query\Query;
use DB;
use Log;
use Tinderbox\ClickhouseBuilder\Query\TwoElementsLogicExpression;

class SmsCampaignContactService
{

    public static function getContacts(SmsCampaignSend $campaignSend): array
    {
        Log::debug('getContacts', ['campaignSend_id' => $campaignSend->id]);
        $settings = $campaignSend->getSettings();
        $limit = '';

        if ($settings->multistep_settings) {
            return self::getContactsForMultistepCampaign($campaignSend);
        }

        return self::getContactsForSimpleCampaign($campaignSend);
    }

    private static function getContactsForMultistepCampaign(SmsCampaignSend $campaignSend): array
    {
        $settings = $campaignSend->getMultistepSettings();
        $limit = $settings->step_size;
        $status = $campaignSend->getMultistepStatus();
        Log::debug('getContactsForMultistepCampaign', ['status' => $status]);
        $baseQuery = self::getBaseQuery($campaignSend);

        if ($status->current_step === 0) {
            $total_available_contacts = ClickhouseService::query("select count(*) as count from ({$baseQuery})");
            $total_available_contacts = $total_available_contacts[0]['count'];
            $status->total_available_contacts = $total_available_contacts;
            if ($total_available_contacts == 0) {
                $status->status = SmsCampaignStatusEnum::sent()->value;
                $campaignSend->setMultistepStatus($status);
                return [];
            }
            Log::debug("total_available_contacts: {$total_available_contacts}");
            if ($total_available_contacts < $limit) {
                Log::debug("last step {$total_available_contacts} < {$limit}");
                $status->status = SmsCampaignStatusEnum::sent()->value;
                $campaignSend->setMultistepStatus($status);
            }


            //get brands and count from baseQuery
            $query =
                "select count(*) as count, network_brand from ({$baseQuery}) group by network_brand order by count asc";
            $brands = ClickhouseService::query($query, 'network_brand');
            $brands = collect($brands)->map(function ($item) {
                return $item['count'];
            })->toArray();
            $status->initial_brands = $brands;

            Log::debug("segment network brands", ['brands' => $brands]);
        }

        if ($status->current_step > 0) {
            //todo - check performance of network brands and disable underperforming brands
        }

        $baseQuery = self::getBaseQuery($campaignSend);
        $query = "select * from ({$baseQuery})";
        Log::debug('CH query', ['query' => $query]);

        $status->total_sent += $limit;
        $status->last_sent_timestamp = time();
        return self::queryContacts($campaignSend, $query, $limit);
    }

    private static function getBaseQuery(SmsCampaignSend $campaignSend)
    {
        $settings = $campaignSend->getSettings();
        $segmentQueryStr = '1=1';
        $segmentQueries = collect([]);
        if (count($settings->segment_ids) > 0) {
            Log::debug('segment_ids', ['segment_ids' => $settings->segment_ids]);
            foreach ($settings->segment_ids as $segmentId) {
                /** @var Segment $segment */
                $segment = Segment::where('id', $segmentId)->first();
                $segmentQueries[] = $segment->getWhereFromSegment()->toSql();
                Log::debug("segmet query", ['query' => $segment->getWhereFromSegment()->toSql()]);
                Log::debug("segment meta", ['meta' => $segment->meta]);
            }

            $segmentQueryStr = $segmentQueries->implode(' OR ');
        }

        return "
        SELECT 
            team_id,
phone_normalized,
 contact_id,
 foreign_id,
last_sent,
last_clicked,
 sent_count,
 clicked_count,
 leads_count,
 sales_count,
 profit_sum,
 network_brand,
 network_id,
network_reason,
 phone_is_good,
 phone_is_good_reason,
 name,
 country_id,
 state_id,
state_id_reason,
 custom1_str,
 custom2_str,
 custom3_str,
 custom4_str,
 custom5_str,
 custom1_int,
 custom2_int,
 custom3_int,
 custom4_int,
 custom5_int,
 custom1_dec,
 custom2_dec,
 custom1_datetime,
 custom2_datetime,
 custom3_datetime,
 custom4_datetime,
 custom5_datetime,
 meta,
 date_created,
 date_updated,
 is_deleted
        from contacts_sms_view
        where team_id = '{$campaignSend->campaign->team_id}'
          AND ({$segmentQueryStr})
        and is_deleted = 0";
    }

    private static function queryContacts(SmsCampaignSend $campaignSend, string $query, int $limit): array
    {
        if ($limit == 0) {
            $limit = 9999999999;
        }
        /** @var Client $client */
        $client = DB::connection('clickhouse')->getClient();
        $res = $client->select($query,
            ['limit' => $limit, 'team_id' => $campaignSend->campaign->team_id]);
        Log::debug("queried contacts: " . $res->count(), ['contacts' => array_merge($res->statistics(),
            $res->responseInfo()
        )]);

        return $res->rows();
    }

    private static function getContactsForSimpleCampaign(SmsCampaignSend $campaignSend)
    {
        $settings = $campaignSend->getSettings();

        return self::queryContacts($campaignSend, self::getBaseQuery($campaignSend), $settings->send_amount);
    }
}