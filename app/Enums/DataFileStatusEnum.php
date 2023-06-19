<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self pending()
 * @method static self processing()
 * @method static self completed()
 * @method static self failed()
 */
class DataFileStatusEnum extends Enum
{
    protected static function values()
    {
        return [
            'pending' => 1,
            'processing' => 2,
            'completed' => 3,
            'failed' => 4,
        ];
    }
}