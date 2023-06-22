<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self equal()
 * @method static self not_equal()
 * @method static self in()
 * @method static self not_in()
 * @method static self less()
 * @method static self less_or_equal()
 * @method static self greater()
 * @method static self greater_or_equal()
 * @method static self begins_with()
 * @method static self not_begins_with()
 * @method static self contains()
 * @method static self not_contains()
 * @method static self ends_with()
 * @method static self not_ends_with()
 * @method static self is_empty()
 * @method static self is_not_empty()
 * @method static self is_null()
 * @method static self is_not_null()
 */
class JqBuilderOperatorEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'equal' => 'equals',
            'not_equal' => 'notEquals',
            'in' => 'in',
            'not_in' => 'notIn',
            'less' => 'less',
            'less_or_equal' => 'lessOrEquals',
            'greater' => 'greater',
            'greater_or_equal' => 'greaterOrEquals',
            'begins_with' => 'startsWith',
            'not_begins_with' => '!startsWith',
            'contains' => 'like',
            'not_contains' => 'notLike',
            'ends_with' => 'endsWith',
            'not_ends_with' => '!endsWith',
            'is_empty' => 'empty',
            'is_not_empty' => 'notEmpty',
            'is_null' => 'isNull',
            'is_not_null' => 'isNotNull',
        ];
    }
}
