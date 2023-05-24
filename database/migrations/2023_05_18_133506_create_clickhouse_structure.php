<?php

use Illuminate\Database\Migrations\Migration;

return new class extends \PhpClickHouseLaravel\Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        static::write(
            "CREATE TABLE IF NOT EXISTS msgr.sms_sendlog
    (
        `sms_id` UUID,
        `team_id` UUID,
        `updated_datetime` DateTime,
        `status` String,
        `list_id` UUID,
        `segment_id` UUID,
        `contact_id` UUID,
        `phone_normalized` UInt64,
        `network_id` UInt32,
        `country_id` UInt16,
        `foreign_id` String,
        `fail_reason` String,
        `is_sent` Int8,
        `is_clicked` BOOL,
        `is_lead` BOOL,
        `is_sale` BOOL,
        `profit` Int32,
        `is_unsubscribed` BOOL,
        `unsubscribed_method` String,
        `domain_id` UUID,
        `original_url` String,
        `shortened_url` String,
        `offer_id` UUID,
        `offer_group_id` UUID,
        `campaign_text_id` UUID,
        `final_text` String,
        `plan_id` UUID,
        `rule_id` UUID,
        `rule_reason` String,
        `sender_id` String,
        `sender_id_id` UUID,
        `sms_parts` UInt8,
        `dlr_code` UInt8,
        `dlr_str` String,
        `cost_platform_profit` Decimal(18,15),
        `cost_platform_cost` Decimal(18,15),
        `cost_user_vendor_cost` Decimal(18,15),
        `click_meta` String,
        `time_clicked` DateTime,
        `meta` String
)
ENGINE = MergeTree
PRIMARY KEY (sms_id)
ORDER BY (sms_id)
SETTINGS index_granularity = 8192"
        );

//        `list_id` SimpleAggregateFunction(anyLast, UUID),
//        static::write("
//        CREATE TABLE IF NOT EXISTS msgr.sms_sendlog_materialized
//        (
//        `team_id` UUID,
//        `first_datetime` SimpleAggregateFunction(any, DateTime),
//        `updated_datetime` SimpleAggregateFunction(anyLast, DateTime),
//        `verified_status` SimpleAggregateFunction(anyLast, String),
//        `segment_id` SimpleAggregateFunction(anyLast, UUID),
//        `contact_id` SimpleAggregateFunction(anyLast, UUID),
//        `network_id` SimpleAggregateFunction(anyLast, UUID),
//        `country_id` SimpleAggregateFunction(anyLast, UUID),
//        `campaign_send_id` SimpleAggregateFunction(anyLast, UUID),
//        `foreign_id` SimpleAggregateFunction(anyLast, String),
//        `fail_reason` SimpleAggregateFunction(anyLast, String),
//        `is_clicked` SimpleAggregateFunction(anyLast, Bool),
//        `is_lead` SimpleAggregateFunction(anyLast, Bool),
//        `is_sale` SimpleAggregateFunction(anyLast, Bool),
//        `sale_profit` SimpleAggregateFunction(anyLast, Int32),
//        `is_unsubscribed` SimpleAggregateFunction(anyLast, Bool),
//        `unsubscribed_method` SimpleAggregateFunction(anyLast, String),
//        `domain_id` SimpleAggregateFunction(anyLast, UUID),
//        `original_url` SimpleAggregateFunction(anyLast, String),
//        `shortened_url` SimpleAggregateFunction(anyLast, String),
//        `offer_id` SimpleAggregateFunction(anyLast, UUID),
//        `offer_group_id` SimpleAggregateFunction(anyLast, UUID),
//        `campaign_text_id` SimpleAggregateFunction(anyLast, UUID),
//        `final_text` SimpleAggregateFunction(anyLast, String),
//        `plan_id` SimpleAggregateFunction(anyLast, UUID),
//        `rule_id` SimpleAggregateFunction(anyLast, UUID),
//        `rule_reason` SimpleAggregateFunction(anyLast, String),
//        `sender_id` SimpleAggregateFunction(anyLast, String),
//        `sender_id_id` SimpleAggregateFunction(anyLast, UUID),
//        `sms_parts` SimpleAggregateFunction(anyLast, UInt8),
//        `dlr_code` SimpleAggregateFunction(anyLast, UInt8),
//        `dlr_str` SimpleAggregateFunction(anyLast, String),
//        `cost_platform_profit` SimpleAggregateFunction(anyLast, Decimal(18,15)),
//        `cost_platform_cost` SimpleAggregateFunction(anyLast, Decimal(18,15)),
//        `cost_user_vendor_cost` SimpleAggregateFunction(anyLast, Decimal(18,15))
//        ) engine = SummingMergeTree PRIMARY KEY id
//        ORDER BY id
//        SETTINGS index_granularity = 8192;
//        ");

        static::write("CREATE MATERIALIZED VIEW IF NOT EXISTS sms_sendlog_mv TO contacts_sms_materialized AS
    select team_id,list_id,phone_normalized, sum(is_sent), sum(is_clicked) as clicks_count, sum(is_lead) as leads_count,  
    sum(is_sale) as sales_count, sum(profit) as profit_sum from sms_sendlog group by team_id,list_id,phone_normalized;");


        static::write("create table IF NOT EXISTS contacts (
    `id` UUID,
    `team_id` UUID,
    `list_id` UUID,
    `phone_normalized` UInt64,
    `phone_is_good` UInt8,
    `phone_is_good_reason` UInt8,
    `email_normalized` String,
    `email_is_good` UInt8,
    `email_is_good_reason` UInt8,
    `name` String,
    `country_id` UInt16,
    `state_id` UInt32,
    `state_id_reason` UInt8,
    `custom1_str` String,
    `custom2_str` String,
    `custom3_str` String,
    `custom4_str` String,
    `custom5_str` String,
    `custom1_int` UInt16,
    `custom2_int` UInt16,
    `custom3_int` UInt16,
    `custom4_int` UInt16,
    `custom5_int` UInt16,
    `custom1_dec` Decimal(18,15),
    `custom2_dec` Decimal(18,15),
    `custom1_datetime` DateTime,
    `custom2_datetime` DateTime,
    `custom3_datetime` DateTime,
    `custom4_datetime` DateTime,
    `custom5_datetime` DateTime,
    `date_updated` DateTime,
    `meta` String,
    `is_deleted` Bool
)ENGINE = MergeTree
ORDER BY (team_id)
SETTINGS index_granularity = 8192");

        /**
         *
        //    `email_normalized` SimpleAggregateFunction(anyLast, String),
        //    `email_is_good` SimpleAggregateFunction(anyLast,UInt8),
        //    `email_is_good_reason` SimpleAggregateFunction(anyLast,UInt8),
         */
        static::write('create table IF NOT EXISTS contacts_sms_materialized
(
    `team_id` UUID,
    `list_id` UUID,
    `phone_normalized` UInt64,
    `last_sent` SimpleAggregateFunction(sum, UInt64),
    `last_clicked` SimpleAggregateFunction(sum, UInt64),
    `sent_count` SimpleAggregateFunction(sum, UInt64),
    `clicked_count` SimpleAggregateFunction(sum, UInt64),
    `leads_count` SimpleAggregateFunction(sum, UInt64),
    `sales_count` SimpleAggregateFunction(sum, UInt64),
    `profit_sum` SimpleAggregateFunction(sum, UInt64),
    `network_brand` SimpleAggregateFunction(anyLast, String),
    `network_id` SimpleAggregateFunction(anyLast,UInt8),
    `network_reason` SimpleAggregateFunction(anyLast,UInt8),
    `phone_is_good` SimpleAggregateFunction(anyLast,UInt8),
    `phone_is_good_reason` SimpleAggregateFunction(anyLast,UInt8),
    `name` SimpleAggregateFunction(anyLast,String),
    `country_id` SimpleAggregateFunction(anyLast,UInt16),
    `state_id` SimpleAggregateFunction(anyLast,UInt32),
    `state_id_reason` SimpleAggregateFunction(anyLast,UInt8),
    `custom1_str` SimpleAggregateFunction(anyLast,String),
    `custom2_str` SimpleAggregateFunction(anyLast,String),
    `custom3_str` SimpleAggregateFunction(anyLast,String),
    `custom4_str` SimpleAggregateFunction(anyLast,String),
    `custom5_str` SimpleAggregateFunction(anyLast,String),
    `custom1_int` SimpleAggregateFunction(anyLast,UInt16),
    `custom2_int` SimpleAggregateFunction(anyLast,UInt16),
    `custom3_int` SimpleAggregateFunction(anyLast,UInt16),
    `custom4_int` SimpleAggregateFunction(anyLast,UInt16),
    `custom5_int` SimpleAggregateFunction(anyLast,UInt16),
    `custom1_dec` SimpleAggregateFunction(anyLast,Decimal(18,15)),
    `custom2_dec` SimpleAggregateFunction(anyLast,Decimal(18,15)),
    `custom1_datetime` SimpleAggregateFunction(anyLast,DateTime),
    `custom2_datetime` SimpleAggregateFunction(anyLast,DateTime),
    `custom3_datetime` SimpleAggregateFunction(anyLast,DateTime),
    `custom4_datetime` SimpleAggregateFunction(anyLast,DateTime),
    `custom5_datetime` SimpleAggregateFunction(anyLast,DateTime),
    `date_updated` SimpleAggregateFunction(anyLast,DateTime),
    `date_created` SimpleAggregateFunction(any,DateTime),
    `is_deleted` SimpleAggregateFunction(anyLast,Bool)
)
    engine = SummingMergeTree
        ORDER BY (team_id, country_id, list_id, network_brand, phone_normalized, phone_is_good)
        SETTINGS index_granularity = 8192;
');

        static::write("CREATE MATERIALIZED VIEW IF NOT EXISTS contacts_mv TO contacts_sms_materialized AS
    select team_id, list_id, phone_normalized,  
    anyLast(custom1_str), 
    anyLast(custom2_str), anyLast(custom3_str),
     anyLast(custom4_str), anyLast(custom5_str),
      anyLast(custom1_int), anyLast(custom2_int),
       anyLast(custom3_int), anyLast(custom4_int),
        anyLast(custom5_int), anyLast(custom1_dec), 
        anyLast(custom2_dec), anyLast(custom1_datetime), 
        anyLast(custom2_datetime), anyLast(custom3_datetime), 
        anyLast(custom4_datetime), anyLast(custom5_datetime),
         anyLast(meta), anyLast(is_deleted), 
         anyLast(phone_is_good),  anyLast(phone_is_good_reason), 
         anyLast(name), anyLast(country_id), 
         anyLast(state_id), anyLast(state_id_reason) 
    from contacts group by team_id, list_id, phone_normalized;");

        static::write('create table IF NOT EXISTS contact_tags
(
    team_id UUID,
    contact_id UUID,
    tag String,
    date_created DateTime,
    is_deleted Bool default 0
)ENGINE = MergeTree
    ORDER BY (team_id, contact_id, tag)
SETTINGS index_granularity = 8192');

        static::write('create table IF NOT EXISTS contact_tags_materialized
(
    team_id UUID,
    tag String,
    contact_id UUID,
    is_deleted SimpleAggregateFunction(anyLast,Bool)
)ENGINE = SummingMergeTree
ORDER BY (team_id, tag, contact_id)
SETTINGS index_granularity = 8192');

        static::write("CREATE MATERIALIZED VIEW IF NOT EXISTS contact_tags_mv TO contact_tags_materialized AS
    select team_id, tag, contact_id, max(is_deleted) from contact_tags group by team_id, contact_id, tag;");

        static::write('create table IF NOT EXISTS action_log
(
    id UUID,
    team_id UUID,
    user_id UUID,
    type String,
    related_model UUID,
    text String,
    date_created DateTime DEFAULT now(),
    meta String
        ) ENGINE = MergeTree
ORDER BY (team_id, related_model, date_created)
TTL date_created + INTERVAL 12 MONTH
SETTINGS index_granularity = 8192');

        static::write("create table IF NOT EXISTS balances
(
    id UUID,
    team_id UUID,
    user_id UUID,
    amount DECIMAL64(6),
    meta Nullable(String),
    date_created DateTime DEFAULT now()
) ENGINE = MergeTree
    ORDER BY (team_id)
");

        static::write("create table IF NOT EXISTS balances_teams_materialized
(
    team_id UUID,
    balance SimpleAggregateFunction(sumWithOverflow(), DECIMAL64(6))
) ENGINE = SummingMergeTree
    ORDER BY (team_id)
");
        static::write('create materialized view IF NOT EXISTS balances_teams_mv TO balances_teams_materialized AS
        select team_id, sum(amount) as balance from balances group by team_id
        ');
        static::write('create view IF NOT EXISTS balances_teams_v AS
        select team_id, sum(balance) as balance from balances_teams_materialized group by team_id
        ');

        static::write("create table IF NOT EXISTS v2_contact_unsub_manual (
    `team_id` UUID,
    `unsub_list_id` UUID DEFAULT '00000000-0000-0000-0000-000000000000',
    `phone_normalized` UInt64,
    `date_updated` DateTime,
    `is_deleted` Bool comment 'if removed can be sent again'
)ENGINE = MergeTree
ORDER BY (team_id,unsub_list_id)
SETTINGS index_granularity = 8192");

        static::write("create table IF NOT EXISTS v2_contact_unsub_manual_materialized (
    `team_id` UUID,
    `unsub_list_id` UUID,
    `phone_normalized` UInt64,
    `date_updated` AggregateFunction(anyLast, DateTime),
    `is_deleted` AggregateFunction(anyLast, Bool)
)ENGINE = SummingMergeTree
ORDER BY (team_id, unsub_list_id)
SETTINGS index_granularity = 8192
");

        static::write("CREATE MATERIALIZED VIEW IF NOT EXISTS v2_contact_unsub_manual_mv TO v2_contact_unsub_manual_materialized AS
    select team_id,unsub_list_id, phone_normalized, max(date_updated),max(is_deleted) from v2_contact_unsub_manual group by team_id,unsub_list_id,phone_normalized;");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        return;
    }
};
