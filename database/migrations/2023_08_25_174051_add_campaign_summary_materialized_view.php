<?php

use PhpClickHouseLaravel\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        static::write('drop view if exists campaign_summary_mv');

        static::write('drop table if exists campaign_summary_materialized');

        static::write("CREATE TABLE campaign_summary_materialized
        (
            `date` Date,
            `sms_routing_route_id` UUID,
            `sent_count` Nullable(UInt32),
            `costs` Nullable(Decimal(18, 15)),
            `clicks` Nullable(UInt32),
            `leads` Nullable(UInt32),
            `sales` Nullable(UInt32)
        )
        ENGINE = SummingMergeTree
        ORDER BY (date, sms_routing_route_id)");

        static::write("CREATE MATERIALIZED VIEW msgr.campaign_summary_mv TO msgr.campaign_summary_materialized AS
        SELECT
            date(updated_datetime) AS date,
            sms_routing_route_id,
            SUM(is_sent) AS sent_count,
            SUM(cost_platform_cost) AS costs,
            SUM(is_clicked) AS clicks,
            SUM(is_lead) AS leads,
            SUM(is_sale) AS sales
        FROM msgr.sms_sendlogs
        GROUP BY (date, sms_routing_route_id)");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
