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

        //throws an error.. todo: fix
        //        static::write("create table v2_contact_unsub_manual (
//    `id` UUID,
//    `team_id` UUID,
//    `unsub_list_id` UUID DEFAULT '00000000-0000-0000-0000-000000000000',
//    `phone_normalized` UInt32,
//    `date_updated` DateTime,
//    `is_deleted` Bool comment 'if removed can be sent again'
//)ENGINE = MergeTree
//PRIMARY KEY (id)
//ORDER BY (id)
//SETTINGS index_granularity = 8192");
//
//        static::write("create table v2_contact_unsub_manual_materialized (
//    `id` UUID,
//    `team_id` UUID,
//    `unsub_list_id` UUID,
//    `phone_normalized` AggregateFunction(anyLast, UInt32),
//    `date_updated` AggregateFunction(anyLast, DateTime),
//    `is_deleted` AggregateFunction(anyLast, Bool)
//)ENGINE = SummingMergeTree
//ORDER BY (id, team_id, unsub_list_id)
//SETTINGS index_granularity = 8192
//");
//
//        static::write("CREATE MATERIALIZED VIEW v2_contact_unsub_manual_mv TO v2_contact_unsub_manual_materialized AS
//    select id,team_id,unsub_list_id, toInt32(phone_normalized) as phone_normalized,date_updated,is_deleted from v2_contact_unsub_manual;");
//
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clickhouse_unsub_structure');
    }
};
