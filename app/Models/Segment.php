<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use HasFactory;

    /*
    `team_id` UUID,
    `list_id` UUID,
    `phone_normalized` UInt64,
    `last_sent` SimpleAggregateFunction(anyLast, DateTime),
    `last_clicked` SimpleAggregateFunction(anyLast, DateTime),
    `sent_count` SimpleAggregateFunction(sum, UInt64),
    `clicked_count` SimpleAggregateFunction(sum, UInt64),
    `leads_count` SimpleAggregateFunction(sum, UInt64),
    `sales_count` SimpleAggregateFunction(sum, UInt64),
    `profit_sum` SimpleAggregateFunction(sum, UInt64),
    `network_brand` SimpleAggregateFunction(anyLast, String),
    `network_id` SimpleAggregateFunction(anyLast, UInt8),
    `network_reason` SimpleAggregateFunction(anyLast, UInt8),
    `phone_is_good` SimpleAggregateFunction(anyLast, UInt8),
    `phone_is_good_reason` SimpleAggregateFunction(anyLast, UInt8),
    `name` SimpleAggregateFunction(anyLast, String),
    `country_id` SimpleAggregateFunction(anyLast, UInt16),
    `state_id` SimpleAggregateFunction(anyLast, UInt32),
    `state_id_reason` SimpleAggregateFunction(anyLast, UInt8),
    `custom1_str` SimpleAggregateFunction(anyLast, String),
    `custom2_str` SimpleAggregateFunction(anyLast, String),
    `custom3_str` SimpleAggregateFunction(anyLast, String),
    `custom4_str` SimpleAggregateFunction(anyLast, String),
    `custom5_str` SimpleAggregateFunction(anyLast, String),
    `custom1_int` SimpleAggregateFunction(anyLast, Nullable(Int32)),
    `custom2_int` SimpleAggregateFunction(anyLast, Nullable(Int32)),
    `custom3_int` SimpleAggregateFunction(anyLast, Nullable(Int32)),
    `custom4_int` SimpleAggregateFunction(anyLast, Nullable(Int32)),
    `custom5_int` SimpleAggregateFunction(anyLast, Nullable(Int32)),
    `custom1_dec` SimpleAggregateFunction(anyLast, Decimal(18, 15)),
    `custom2_dec` SimpleAggregateFunction(anyLast, Decimal(18, 15)),
    `custom1_datetime` SimpleAggregateFunction(anyLast, DateTime),
    `custom2_datetime` SimpleAggregateFunction(anyLast, DateTime),
    `custom3_datetime` SimpleAggregateFunction(anyLast, DateTime),
    `custom4_datetime` SimpleAggregateFunction(anyLast, DateTime),
    `custom5_datetime` SimpleAggregateFunction(anyLast, DateTime),
    `date_updated` SimpleAggregateFunction(anyLast, DateTime),
    `date_created` SimpleAggregateFunction(any, DateTime),
    `is_deleted` SimpleAggregateFunction(anyLast, Bool)
    */
}
