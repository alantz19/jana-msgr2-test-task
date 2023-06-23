<?php

namespace App\Services;

use App\Enums\JqBuilderFieldEnum;
use App\Enums\JqBuilderOperatorEnum;
use App\Enums\SegmentTypeEnum;
use App\Models\Segment;
use ClickHouseDB\Query\Degeneration\Bindings;
use ClickHouseDB\Query\Query;
use Illuminate\Support\Facades\Log;
use PhpClickHouseLaravel\Builder;
use PhpClickHouseLaravel\RawColumn;

class SegmentBuilderService
{
    private static int $ruleIdx = 0;

    public static function create(Segment $segment): ?Builder
    {
        $data = $segment->meta['query'] ?? [];

        if (empty($data)) {
            Log::warning('Empty segment query', [
                'segment' => $segment->toArray(),
            ]);
            return null;
        }

        $res = self::parse($data);

        if (empty($res) || empty($res['sql'])) {
            Log::warning("Can't parse segment query", [
                'segment' => $segment->toArray(),
            ]);
            return null;
        }

        $bindings = new Bindings();
        foreach ($res['binds'] as $col => $value) {
            $bindings->bindParam($col, $value);
        }

        $builder = match ($segment->type) {
            SegmentTypeEnum::numbers()->value => self::getBuilderBaseNumbers($segment),
            SegmentTypeEnum::emails()->value => self::getBuilderBaseEmails($segment),
        };
        $builder->whereRaw('(`is_deleted` is null or `is_deleted` = 0)');
        $builder->whereRaw(new Query($res['sql'], [$bindings]));

        return $builder;
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

    private static function getBuilderBaseNumbers(Segment $segment): Builder
    {
        $sub = (new Builder())
            ->select([
                'phone_normalized',
                new RawColumn('anyLast(last_sent)', 'last_sent'),
                new RawColumn('anyLast(last_clicked)', 'last_clicked'),
                new RawColumn('sum(sent_count)', 'sent_count'),
                new RawColumn('sum(clicked_count)', 'clicked_count'),
                new RawColumn('sum(leads_count)', 'leads_count'),
                new RawColumn('sum(sales_count)', 'sales_count'),
                new RawColumn('sum(profit_sum)', 'profit_sum'),
                new RawColumn('anyLast(network_brand)', 'network_brand'),
                new RawColumn('anyLast(network_id)', 'network_id'),
                new RawColumn('anyLast(network_reason)', 'network_reason'),
                new RawColumn('anyLast(phone_is_good)', 'phone_is_good'),
                new RawColumn('anyLast(phone_is_good_reason)', 'phone_is_good_reason'),
                new RawColumn('anyLast(name)', 'name'),
                new RawColumn('anyLast(country_id)', 'country_id'),
                new RawColumn('anyLast(state_id)', 'state_id'),
                new RawColumn('anyLast(state_id_reason)', 'state_id_reason'),
                new RawColumn('anyLast(custom1_str)', 'custom1_str'),
                new RawColumn('anyLast(custom2_str)', 'custom2_str'),
                new RawColumn('anyLast(custom3_str)', 'custom3_str'),
                new RawColumn('anyLast(custom4_str)', 'custom4_str'),
                new RawColumn('anyLast(custom5_str)', 'custom5_str'),
                new RawColumn('anyLast(custom1_int)', 'custom1_int'),
                new RawColumn('anyLast(custom2_int)', 'custom2_int'),
                new RawColumn('anyLast(custom3_int)', 'custom3_int'),
                new RawColumn('anyLast(custom4_int)', 'custom4_int'),
                new RawColumn('anyLast(custom5_int)', 'custom5_int'),
                new RawColumn('anyLast(custom1_dec)', 'custom1_dec'),
                new RawColumn('anyLast(custom2_dec)', 'custom2_dec'),
                new RawColumn('anyLast(custom1_datetime)', 'custom1_datetime'),
                new RawColumn('anyLast(custom2_datetime)', 'custom2_datetime'),
                new RawColumn('anyLast(custom3_datetime)', 'custom3_datetime'),
                new RawColumn('anyLast(custom4_datetime)', 'custom4_datetime'),
                new RawColumn('anyLast(custom5_datetime)', 'custom5_datetime'),
                new RawColumn('any(date_created)', 'date_created'),
                new RawColumn('anyLast(date_updated)', 'date_updated'),
                new RawColumn('anyLast(is_deleted)', 'is_deleted'),
            ])
            ->from('contacts_sms_materialized')
            ->where('team_id', $segment->team_id)
            ->groupBy('phone_normalized');

        return (new Builder())
            ->from(function($from) use ($sub) {
                $from->query($sub);
            });
    }

    private static function getBuilderBaseEmails(Segment $segment): Builder
    {
        // @TODO
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
        $op = JqBuilderOperatorEnum::from($rule['operator']);
        $field = JqBuilderFieldEnum::from($rule['field']);
        $bindKey = 'rule_' . self::$ruleIdx;
        $value = $rule['value'];

        if ($op->equals(JqBuilderOperatorEnum::contains(), JqBuilderOperatorEnum::not_contains())) {
            $value = "%$value%";
        }

        $sql = $op->toSql($field, $bindKey);

        return [
            'field' => $field,
            'operator' => $op->value,
            'value' => $value,
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