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
            "CREATE TABLE msgr.sms_sendlog
    (
        `id` UUID,
        `team_id` UUID,
        `updated_datetime` DateTime,
        `status` String,
        `list_id` UUID,
        `segment_id` UUID,
        `contact_id` UUID,
        `phone_normalized` UInt64,
        `network_id` UUID,
        `country_id` UUID,
        `campaign_send_id` UUID,
        `foreign_id` String,
        `fail_reason` String,
        `is_sent` Int8,
        `is_clicked` BOOL,
        `is_lead` BOOL,
        `is_sale` BOOL,
        `sale_profit` Int32,
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
        `meta` String
)
ENGINE = MergeTree
PRIMARY KEY (id)
ORDER BY (id)
SETTINGS index_granularity = 8192"
        );

        static::write("
        CREATE TABLE msgr.sms_sendlog_materialized
        (
        `id` UUID,
        `team_id` SimpleAggregateFunction(anyLast, UUID),
        `first_datetime` SimpleAggregateFunction(any, DateTime),
        `updated_datetime` SimpleAggregateFunction(anyLast, DateTime),
        `status` SimpleAggregateFunction(anyLast, String),
        `list_id` SimpleAggregateFunction(anyLast, UUID),
        `segment_id` SimpleAggregateFunction(anyLast, UUID),
        `contact_id` SimpleAggregateFunction(anyLast, UUID),
        `phone_normalized` SimpleAggregateFunction(anyLast, UInt64),
        `network_id` SimpleAggregateFunction(anyLast, UUID),
        `country_id` SimpleAggregateFunction(anyLast, UUID),
        `campaign_send_id` SimpleAggregateFunction(anyLast, UUID),
        `foreign_id` SimpleAggregateFunction(anyLast, String),
        `fail_reason` SimpleAggregateFunction(anyLast, String),
        `is_clicked` SimpleAggregateFunction(anyLast, Bool),
        `is_lead` SimpleAggregateFunction(anyLast, Bool),
        `is_sale` SimpleAggregateFunction(anyLast, Bool),
        `sale_profit` SimpleAggregateFunction(anyLast, Int32),
        `is_unsubscribed` SimpleAggregateFunction(anyLast, Bool),
        `unsubscribed_method` SimpleAggregateFunction(anyLast, String),
        `domain_id` SimpleAggregateFunction(anyLast, UUID),
        `original_url` SimpleAggregateFunction(anyLast, String),
        `shortened_url` SimpleAggregateFunction(anyLast, String),
        `offer_id` SimpleAggregateFunction(anyLast, UUID),
        `offer_group_id` SimpleAggregateFunction(anyLast, UUID),
        `campaign_text_id` SimpleAggregateFunction(anyLast, UUID),
        `final_text` SimpleAggregateFunction(anyLast, String),
        `plan_id` SimpleAggregateFunction(anyLast, UUID),
        `rule_id` SimpleAggregateFunction(anyLast, UUID),
        `rule_reason` SimpleAggregateFunction(anyLast, String),
        `sender_id` SimpleAggregateFunction(anyLast, String),
        `sender_id_id` SimpleAggregateFunction(anyLast, UUID),
        `sms_parts` SimpleAggregateFunction(anyLast, UInt8),
        `dlr_code` SimpleAggregateFunction(anyLast, UInt8),
        `dlr_str` SimpleAggregateFunction(anyLast, String),
        `cost_platform_profit` SimpleAggregateFunction(anyLast, Decimal(18,15)),
        `cost_platform_cost` SimpleAggregateFunction(anyLast, Decimal(18,15)),
        `cost_user_vendor_cost` SimpleAggregateFunction(anyLast, Decimal(18,15))
        ) engine = SummingMergeTree PRIMARY KEY id
        ORDER BY id
        SETTINGS index_granularity = 8192;
        ");

        static::write("CREATE MATERIALIZED VIEW sms_sendlog_mv TO sms_sendlog_materialized AS
    select * from sms_sendlog;");


        static::write("create table contact (
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
    `country_id` UUID,
    `state_id` UUID,
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
    `is_deleted` Bool
)ENGINE = MergeTree
ORDER BY (team_id)
SETTINGS index_granularity = 8192");

        static::write('create table contact_materialized
(
    `id` UUID,
    `team_id` SimpleAggregateFunction(anyLast,UUID) comment \'if null then global unsub\',
    `list_id` SimpleAggregateFunction(anyLast,UUID),
    `phone_normalized` SimpleAggregateFunction(anyLast, UInt64),
    `phone_is_good` SimpleAggregateFunction(anyLast,UInt8),
    `phone_is_good_reason` SimpleAggregateFunction(anyLast,UInt8),
    `email_normalized` SimpleAggregateFunction(anyLast, String),
    `email_is_good` SimpleAggregateFunction(anyLast,UInt8),
    `email_is_good_reason` SimpleAggregateFunction(anyLast,UInt8),
    `name` SimpleAggregateFunction(anyLast,String),
    `country_id` SimpleAggregateFunction(anyLast,UUID),
    `state_id` SimpleAggregateFunction(anyLast,UUID),
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
        ORDER BY id
        SETTINGS index_granularity = 8192;
');

        static::write("CREATE MATERIALIZED VIEW contact_mv TO contact_materialized AS
    select * from contact;");

        static::write('create table contact_tag
(
    id UUID,
    team_id UUID,
    contact_id UUID,
    tag String,
    date_created DateTime,
    is_deleted Bool
)ENGINE = MergeTree
ORDER BY (id)
SETTINGS index_granularity = 8192');

        static::write('create table contact_tag_materialized
(
    id UUID,
    team_id SimpleAggregateFunction(anyLast,UUID),
    contact_id SimpleAggregateFunction(anyLast,UUID),
    tag SimpleAggregateFunction(anyLast,String),
    date_created SimpleAggregateFunction(anyLast,DateTime),
    is_deleted SimpleAggregateFunction(anyLast,Bool)
)ENGINE = SummingMergeTree
ORDER BY (id)
SETTINGS index_granularity = 8192');
        static::write("CREATE MATERIALIZED VIEW contact_tag_mv TO contact_tag_materialized AS
    select * from contact;");

        static::write('create table action_log
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

        static::write("create table balances
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

        static::write("create table balances_teams_materialized
(
    team_id UUID,
    balance SimpleAggregateFunction(sumWithOverflow(), DECIMAL64(6))
) ENGINE = SummingMergeTree
    ORDER BY (team_id)
");
        static::write('create materialized view balances_teams_mv TO balances_teams_materialized AS
        select team_id, sum(amount) as balance from balances group by team_id
        ');
        static::write('create view balances_teams_v AS
        select team_id, sum(balance) as balance from balances_teams_materialized group by team_id
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        return;
    }
};
