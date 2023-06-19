<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self numbers()
 * @method static self emails()
 */
class DataFileTypeEnum extends Enum
{
    protected static function values()
    {
        return [
            'numbers' => 1,
            'emails' => 2,
        ];
    }
}
