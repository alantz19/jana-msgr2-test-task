<?php

namespace App\Models\Clickhouse\Materialized;

use PhpClickHouseLaravel\BaseModel;
use PhpClickHouseLaravel\Builder;
use PhpClickHouseLaravel\RawColumn;

class ContactSms extends BaseModel
{
    protected $table = 'contacts_sms_materialized';

    public static function findOne(string $teamId, string $phone): ?array
    {
        $sub = (new static())::select([
            'phone_normalized',
            'team_id',
            new RawColumn('anyLast(id)', 'id'),
            new RawColumn('max(last_sent)', 'last_sent'),
            new RawColumn('max(last_clicked)', 'last_clicked'),
            new RawColumn('sum(sent_count)', 'sent_count'),
            new RawColumn('sum(clicked_count)', 'clicked_count'),
            new RawColumn('sum(leads_count)', 'leads_count'),
            new RawColumn('sum(sales_count)', 'sales_count'),
            new RawColumn('sum(profit_sum)', 'profit_sum'),
            new RawColumn('anyLast(network_brand)', 'network_brand'),
            new RawColumn('anyLast(network_id)', 'network_id'),
            new RawColumn('anyLast(network_reason)', 'network_reason'),
            new RawColumn('anyLast(phone_is_good)', 'phone_is_good'),
            new RawColumn('anyLast(phone_is_good_reason)', 'phone_is_good_reason'),
            new RawColumn('anyLast(name)', 'name'),
            new RawColumn('anyLast(country_id)', 'country_id'),
            new RawColumn('anyLast(state_id)', 'state_id'),
            new RawColumn('anyLast(state_id_reason)', 'state_id_reason'),
            new RawColumn('anyLast(custom1_str)', 'custom1_str'),
            new RawColumn('anyLast(custom2_str)', 'custom2_str'),
            new RawColumn('anyLast(custom3_str)', 'custom3_str'),
            new RawColumn('anyLast(custom4_str)', 'custom4_str'),
            new RawColumn('anyLast(custom5_str)', 'custom5_str'),
            new RawColumn('anyLast(custom1_int)', 'custom1_int'),
            new RawColumn('anyLast(custom2_int)', 'custom2_int'),
            new RawColumn('anyLast(custom3_int)', 'custom3_int'),
            new RawColumn('anyLast(custom4_int)', 'custom4_int'),
            new RawColumn('anyLast(custom5_int)', 'custom5_int'),
            new RawColumn('anyLast(custom1_dec)', 'custom1_dec'),
            new RawColumn('anyLast(custom2_dec)', 'custom2_dec'),
            new RawColumn('anyLast(custom1_datetime)', 'custom1_datetime'),
            new RawColumn('anyLast(custom2_datetime)', 'custom2_datetime'),
            new RawColumn('anyLast(custom3_datetime)', 'custom3_datetime'),
            new RawColumn('anyLast(custom4_datetime)', 'custom4_datetime'),
            new RawColumn('anyLast(custom5_datetime)', 'custom5_datetime'),
            new RawColumn('anyLast(meta)', 'meta'),
            new RawColumn('any(date_created)', 'date_created'),
            new RawColumn('anyLast(date_updated)', 'date_updated'),
            new RawColumn('anyLast(is_deleted)', 'is_deleted'),
        ])
            ->where('team_id', $teamId)
            ->where('phone_normalized', $phone)
            ->groupBy(['phone_normalized', 'team_id']);

        $query = (new Builder())
            ->from(function ($from) use ($sub) {
                $from->query($sub);
            }, 'csm');

        if ($array = $query->get()->fetchOne()) {
            return $array;
        }

        return null;
    }
}
