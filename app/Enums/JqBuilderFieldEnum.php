<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self number_clicks_count()
 * @method static self number_country()
 * @method static self number_network_id()
 * @method static self number_network_brand()
 * @method static self number_date_created()
 */
class JqBuilderFieldEnum extends Enum
{
    public static function values()
    {
        return [
            'number_clicks_count' => 'clicks_count',
            'number_country' => 'country_id',
            'number_network_id' => 'network_id',
            'number_network_brand' => 'network_brand',
        ];
    }
}
