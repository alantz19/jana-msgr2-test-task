<?php


use PhpClickHouseLaravel\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        static::write('drop view if exists contacts_sms_mv');
        static::write('drop view if exists contacts_sms_view');

        static::write('alter table contacts rename column if exists id to contact_id');
        static::write('alter table contacts_sms_materialized rename column if exists id to contact_id');

        static::write("CREATE MATERIALIZED VIEW msgr.contacts_sms_mv TO msgr.contacts_sms_materialized
(
    `team_id` UUID,
    `phone_normalized` UInt64,
    `contact_id` Nullable(UUID),
    `foreign_id` Nullable(String),
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
    `is_deleted` Int64
) AS
SELECT
    team_id,
    phone_normalized,
    contact_id,
    foreign_id,
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
    date_created,
    date_updated,
    meta,
    is_deleted
FROM msgr.contacts
WHERE phone_normalized > 0");

        static::write("CREATE VIEW msgr.contacts_sms_view
(
    `team_id` UUID,
    `phone_normalized` UInt64,
    `contact_id` Nullable(UUID),
    `foreign_id` Nullable(String),
    `last_sent` Nullable(DateTime),
    `last_clicked` Nullable(DateTime),
    `sent_count` UInt64,
    `clicked_count` UInt64,
    `leads_count` UInt64,
    `sales_count` UInt64,
    `profit_sum` UInt64,
    `network_brand` Nullable(String),
    `network_id` Nullable(UInt32),
    `network_reason` Nullable(UInt8),
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
    `meta` Nullable(String),
    `date_created` Nullable(DateTime),
    `date_updated` Nullable(DateTime),
    `is_deleted` Int64
) AS
SELECT
    team_id,
    phone_normalized,
    argMax(contact_id, inserted_at) AS contact_id,
    argMax(foreign_id, inserted_at) AS foreign_id,
    argMax(last_sent, inserted_at) AS last_sent,
    argMax(last_clicked, inserted_at) AS last_clicked,
    sum(sent_count) AS sent_count,
    sum(clicked_count) AS clicked_count,
    sum(leads_count) AS leads_count,
    sum(sales_count) AS sales_count,
    sum(profit_sum) AS profit_sum,
    argMax(network_brand, inserted_at) AS network_brand,
    argMax(network_id, inserted_at) AS network_id,
    argMax(network_reason, inserted_at) AS network_reason,
    argMax(phone_is_good, inserted_at) AS phone_is_good,
    argMax(phone_is_good_reason, inserted_at) AS phone_is_good_reason,
    argMax(name, inserted_at) AS name,
    argMax(country_id, inserted_at) AS country_id,
    argMax(state_id, inserted_at) AS state_id,
    argMax(state_id_reason, inserted_at) AS state_id_reason,
    argMax(custom1_str, inserted_at) AS custom1_str,
    argMax(custom2_str, inserted_at) AS custom2_str,
    argMax(custom3_str, inserted_at) AS custom3_str,
    argMax(custom4_str, inserted_at) AS custom4_str,
    argMax(custom5_str, inserted_at) AS custom5_str,
    argMax(custom1_int, inserted_at) AS custom1_int,
    argMax(custom2_int, inserted_at) AS custom2_int,
    argMax(custom3_int, inserted_at) AS custom3_int,
    argMax(custom4_int, inserted_at) AS custom4_int,
    argMax(custom5_int, inserted_at) AS custom5_int,
    argMax(custom1_dec, inserted_at) AS custom1_dec,
    argMax(custom2_dec, inserted_at) AS custom2_dec,
    argMax(custom1_datetime, inserted_at) AS custom1_datetime,
    argMax(custom2_datetime, inserted_at) AS custom2_datetime,
    argMax(custom3_datetime, inserted_at) AS custom3_datetime,
    argMax(custom4_datetime, inserted_at) AS custom4_datetime,
    argMax(custom5_datetime, inserted_at) AS custom5_datetime,
    argMax(meta, inserted_at) AS meta,
    argMax(date_created, inserted_at) AS date_created,
    argMax(date_updated, inserted_at) AS date_updated,
    sum(is_deleted) AS is_deleted
FROM msgr.contacts_sms_materialized
GROUP BY
    phone_normalized,
    team_id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
