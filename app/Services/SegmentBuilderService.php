<?php

namespace App\Services;

use App\Enums\JqBuilderFieldEnum;
use App\Enums\JqBuilderOperatorEnum;
use App\Models\Segment;
use ClickHouseDB\Query\Degeneration\Bindings;
use ClickHouseDB\Query\Query;
use Illuminate\Support\Facades\Log;
use PhpClickHouseLaravel\Builder;

class SegmentBuilderService
{
    private static int $ruleIdx = 0;

    public static function buildQuery(Segment $segment): ?Builder
    {
        $data = $segment->meta['query'] ?? [];

        if (empty($data)) {
            throw new \Exception('Empty segment query');
        }

        // @TODO write base query
        $sql = '';
        $res = self::parse($data);

        if (empty($res)) {
            throw new \Exception('Empty segment query');
        }

        $builder = new Builder();
        // @TODO rewrite to Builder

        $bindings = new Bindings();
        foreach ($res['binds'] as $col => $value) {
            $bindings->bindParam($col, $value);
        }

        return new Query($sql . $res['sql'], [$bindings]);
    }

    public static function parse(array $conditions): array
    {
        $data = [];

        if (self::isGroup($conditions)) {
            $arr = [
                'condition' => $conditions['condition'],
                'rules' => self::parse($conditions['rules']),
            ];
            $arr['sql'] = self::getGroupSql($arr);
            $arr['binds'] = self::getGroupBinds($arr);
            return $arr;
        }

        foreach ($conditions as $rule) {
            if (self::isGroup($rule)) {
                $data[] = self::parse($rule);
                continue;
            }

            if (self::isRule($rule)) {
                $data[] = self::parseRule($rule);
                self::$ruleIdx += 1;
                continue;
            }

            Log::warning('Unknown rule', [
                'rule' => $rule,
            ]);
        }

        return $data;
    }

    private static function isGroup($value): bool
    {
        return is_array($value) && array_key_exists('condition', $value);
    }

    private static function isRule($value): bool
    {
        return is_array($value)
            && array_key_exists('operator', $value)
            && array_key_exists('field', $value)
            && array_key_exists('value', $value);
    }

    private static function parseRule(array $rule): ?array
    {
        $op = JqBuilderOperatorEnum::from($rule['operator'])->value;
        $field = JqBuilderFieldEnum::from($rule['field'])->value;
        $bindKey = 'rule_' . self::$ruleIdx;

        // @TODO format date, replace field with dictGet if required

        $sql = match ($op) {
            JqBuilderOperatorEnum::equal()->value,
            JqBuilderOperatorEnum::not_equal()->value,
            JqBuilderOperatorEnum::less()->value,
            JqBuilderOperatorEnum::less_or_equal()->value,
            JqBuilderOperatorEnum::greater()->value,
            JqBuilderOperatorEnum::greater_or_equal()->value,
            JqBuilderOperatorEnum::begins_with()->value,
            JqBuilderOperatorEnum::not_begins_with()->value,
            JqBuilderOperatorEnum::ends_with()->value,
            JqBuilderOperatorEnum::not_ends_with()->value => "$op($field, :$bindKey)",

            JqBuilderOperatorEnum::contains()->value,
            JqBuilderOperatorEnum::not_contains()->value => "$field $op %:$bindKey%",

            JqBuilderOperatorEnum::in()->value,
            JqBuilderOperatorEnum::not_in()->value => "$field $op(:$bindKey)",

            JqBuilderOperatorEnum::is_empty()->value,
            JqBuilderOperatorEnum::is_not_empty()->value,
            JqBuilderOperatorEnum::is_null()->value,
            JqBuilderOperatorEnum::is_not_null()->value => "$op($field)",
        };

        return [
            'field' => $field,
            'operator' => $op,
            'value' => $rule['value'],
            'sql' => $sql,
            'bind_key' => $bindKey,
        ];
    }

    private static function getGroupSql($group): string
    {
        $rules = [];
        foreach ($group['rules'] as $rule) {
            if (self::isGroup($rule)) {
                $rules[] = self::getGroupSql($rule);
                continue;
            }

            $rules[] = $rule['sql'];
        }

        return '(' . implode(' ' . $group['condition'] . ' ', $rules) . ')';
    }

    private static function getGroupBinds($group): array
    {
        $binds = [];

        foreach ($group['rules'] as $rule) {
            if (self::isGroup($rule)) {
                $binds = array_merge($binds, self::getGroupBinds($rule));
                continue;
            }

            $binds[$rule['bind_key']] = $rule['value'];
        }

        return $binds;
    }
}