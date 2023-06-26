<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \PhpClickHouseLaravel\Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        static::write('drop view if exists msgr.contacts_mv');
        static::write('drop view if exists msgr.contacts_sms_mv');
        static::write('drop table if exists msgr.contacts');
        static::write('drop table if exists msgr.contacts_sms_materialized');

        static::write("CREATE TABLE msgr.contacts
(
    `team_id` UUID,
    `id` Nullable(UUID),
    `foreign_id` Nullable(String),
    `phone_normalized` Nullable(UInt64),
    `phone_is_good` Nullable(UInt8),
    `phone_is_good_reason` Nullable(UInt8),
    `email_normalized` Nullable(String),
    `email_is_good` Nullable(UInt8),
    `email_is_good_reason` Nullable(UInt8),
    `name` Nullable(String),
    `country_id` Nullable(UInt32),
    `state_id` Nullable(UInt32),
    `state_id_reason` Nullable(UInt8),
    `custom1_str` Nullable(String),
    `custom2_str` Nullable(String),
    `custom3_str` Nullable(String),
    `custom4_str` Nullable(String),
    `custom5_str` Nullable(String),
    `custom1_int` Nullable(Int32),
    `custom2_int` Nullable(Int32),
    `custom3_int` Nullable(Int32),
    `custom4_int` Nullable(Int32),
    `custom5_int` Nullable(Int32),
    `custom1_dec` Nullable(Decimal(18, 15)),
    `custom2_dec` Nullable(Decimal(18, 15)),
    `custom1_datetime` Nullable(DateTime),
    `custom2_datetime` Nullable(DateTime),
    `custom3_datetime` Nullable(DateTime),
    `custom4_datetime` Nullable(DateTime),
    `custom5_datetime` Nullable(DateTime),
    `date_created` Nullable(DateTime),
    `date_updated` Nullable(DateTime),
    `meta` Nullable(String),
    `tags` Nullable(String),
    `is_deleted` Nullable(Bool)
)
ENGINE = MergeTree
ORDER BY team_id
SETTINGS index_granularity = 8192");

        static::write("CREATE TABLE msgr.contacts_sms_materialized
(
    `team_id` UUID,
    `phone_normalized` UInt64,
    `id` SimpleAggregateFunction(anyLast, Nullable(UUID)),
    `foreign_id` SimpleAggregateFunction(anyLast, Nullable(UUID)),
    `last_sent` SimpleAggregateFunction(max, Nullable(DateTime)),
    `last_clicked` SimpleAggregateFunction(max, Nullable(DateTime)),
    `sent_count` SimpleAggregateFunction(sum, UInt64),
    `clicked_count` SimpleAggregateFunction(sum, UInt64),
    `leads_count` SimpleAggregateFunction(sum, UInt64),
    `sales_count` SimpleAggregateFunction(sum, UInt64),
    `profit_sum` SimpleAggregateFunction(sum, UInt64),
    `network_brand` SimpleAggregateFunction(anyLast, Nullable(String)),
    `network_id` SimpleAggregateFunction(anyLast, Nullable(UInt32)),
    `network_reason` SimpleAggregateFunction(anyLast, Nullable(UInt8)),
    `phone_is_good` SimpleAggregateFunction(anyLast, Nullable(UInt8)),
    `phone_is_good_reason` SimpleAggregateFunction(anyLast, Nullable(UInt8)),
    `name` SimpleAggregateFunction(anyLast, Nullable(String)),
    `country_id` SimpleAggregateFunction(anyLast, Nullable(UInt32)),
    `state_id` SimpleAggregateFunction(anyLast, Nullable(UInt32)),
    `state_id_reason` SimpleAggregateFunction(anyLast, Nullable(UInt8)),
    `custom1_str` SimpleAggregateFunction(anyLast, Nullable(String)),
    `custom2_str` SimpleAggregateFunction(anyLast, Nullable(String)),
    `custom3_str` SimpleAggregateFunction(anyLast, Nullable(String)),
    `custom4_str` SimpleAggregateFunction(anyLast, Nullable(String)),
    `custom5_str` SimpleAggregateFunction(anyLast, Nullable(String)),
    `custom1_int` SimpleAggregateFunction(anyLast, Nullable(Int32)),
    `custom2_int` SimpleAggregateFunction(anyLast, Nullable(Int32)),
    `custom3_int` SimpleAggregateFunction(anyLast, Nullable(Int32)),
    `custom4_int` SimpleAggregateFunction(anyLast, Nullable(Int32)),
    `custom5_int` SimpleAggregateFunction(anyLast, Nullable(Int32)),
    `custom1_dec` SimpleAggregateFunction(anyLast, Nullable(Decimal(18, 15))),
    `custom2_dec` SimpleAggregateFunction(anyLast, Nullable(Decimal(18, 15))),
    `custom1_datetime` SimpleAggregateFunction(anyLast, Nullable(DateTime)),
    `custom2_datetime` SimpleAggregateFunction(anyLast, Nullable(DateTime)),
    `custom3_datetime` SimpleAggregateFunction(anyLast, Nullable(DateTime)),
    `custom4_datetime` SimpleAggregateFunction(anyLast, Nullable(DateTime)),
    `custom5_datetime` SimpleAggregateFunction(anyLast, Nullable(DateTime)),
    `date_created` SimpleAggregateFunction(any, Nullable(DateTime)),
    `date_updated` SimpleAggregateFunction(anyLast, Nullable(DateTime)),
    `meta` SimpleAggregateFunction(anyLast, Nullable(String)),
    `tags` SimpleAggregateFunction(anyLast, Nullable(String)),
    `is_deleted` SimpleAggregateFunction(anyLast, Nullable(Bool))
)
ENGINE = SummingMergeTree
ORDER BY (team_id, phone_normalized)
SETTINGS index_granularity = 8192");

        static::write("CREATE MATERIALIZED VIEW msgr.contacts_sms_mv TO msgr.contacts_sms_materialized
(
    `team_id` UUID,
    `phone_normalized` UInt64,
    `id` Nullable(UUID),
    `phone_is_good` Nullable(UInt8),
    `phone_is_good_reason` Nullable(UInt8),
    `name` Nullable(String),
    `country_id` Nullable(UInt32),
    `state_id` Nullable(UInt32),
    `state_id_reason` Nullable(UInt8),
    `custom1_str` Nullable(String),
    `custom2_str` Nullable(String),
    `custom3_str` Nullable(String),
    `custom4_str` Nullable(String),
    `custom5_str` Nullable(String),
    `custom1_int` Nullable(Int32),
    `custom2_int` Nullable(Int32),
    `custom3_int` Nullable(Int32),
    `custom4_int` Nullable(Int32),
    `custom5_int` Nullable(Int32),
    `custom1_dec` Nullable(Decimal(18, 15)),
    `custom2_dec` Nullable(Decimal(18, 15)),
    `custom1_datetime` Nullable(DateTime),
    `custom2_datetime` Nullable(DateTime),
    `custom3_datetime` Nullable(DateTime),
    `custom4_datetime` Nullable(DateTime),
    `custom5_datetime` Nullable(DateTime),
    `date_created` Nullable(DateTime),
    `date_updated` Nullable(DateTime),
    `meta` Nullable(String),
    `tags` Nullable(String),
    `is_deleted` Nullable(Bool)
) AS
SELECT
    `team_id`,
    `phone_normalized`,
    `id`,
    `phone_is_good`,
    `phone_is_good_reason`,
    `name`,
    `country_id`,
    `state_id`,
    `state_id_reason`,
    `custom1_str`,
    `custom2_str`,
    `custom3_str`,
    `custom4_str`,
    `custom5_str`,
    `custom1_int`,
    `custom2_int`,
    `custom3_int`,
    `custom4_int`,
    `custom5_int`,
    `custom1_dec`,
    `custom2_dec`,
    `custom1_datetime`,
    `custom2_datetime`,
    `custom3_datetime`,
    `custom4_datetime`,
    `custom5_datetime`,
    `date_created`,
    `date_updated`,
    `meta`,
    `tags`,
    `is_deleted`
FROM msgr.contacts
WHERE `phone_normalized` > 0");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
