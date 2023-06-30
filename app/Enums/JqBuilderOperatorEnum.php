<?php

namespace App\Enums;

use App\Models\Clickhouse\Views\ContactTagView;
use App\Models\Segment;
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
            'not_in' => 'not in',
            'less' => 'less',
            'less_or_equal' => 'lessOrEquals',
            'greater' => 'greater',
            'greater_or_equal' => 'greaterOrEquals',
            'begins_with' => 'startsWith',
            'not_begins_with' => '!startsWith',
            'contains' => 'like',
            'not_contains' => 'not like',
            'ends_with' => 'endsWith',
            'not_ends_with' => '!endsWith',
            'is_empty' => 'empty',
            'is_not_empty' => 'notEmpty',
            'is_null' => 'isNull',
            'is_not_null' => 'isNotNull',
        ];
    }

    public function toSql(Segment $segment, JqBuilderFieldEnum $field, string $bindKey): string
    {
        $fieldValue = $field->value;

        if ($field->equals(JqBuilderFieldEnum::date_created())) {
            $fieldValue = "toDate($fieldValue)";
            $bindKey = "parseDateTime32BestEffort(:$bindKey)";
        } else if ($field->equals(JqBuilderFieldEnum::tags())) {
            $tagOp = match ($this->value) {
                self::in()->value => '=',
                self::not_in()->value => '!=',
            };
            $sub = ContactTagView::select('contact_id')
                ->where('team_id', $segment->team_id)
                ->where('is_deleted', 0)
                ->whereRaw("tag $tagOp :$bindKey")
                ->toSql();
            return "id $this->value ($sub)";
        } else {
            $bindKey = ":$bindKey";
        }

        return match ($this->value) {
            self::equal()->value,
            self::not_equal()->value,
            self::less()->value,
            self::less_or_equal()->value,
            self::greater()->value,
            self::greater_or_equal()->value,
            self::begins_with()->value,
            self::not_begins_with()->value,
            self::ends_with()->value,
            self::not_ends_with()->value => "$this->value($fieldValue, $bindKey)",

            self::contains()->value,
            self::not_contains()->value => "$fieldValue $this->value $bindKey",

            self::in()->value,
            self::not_in()->value => "$fieldValue $this->value($bindKey)",

            self::is_empty()->value,
            self::is_not_empty()->value,
            self::is_null()->value,
            self::is_not_null()->value => "$this->value($fieldValue)",
        };
    }
}
