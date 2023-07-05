<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self clicked_count()
 * @method static self sent_count()
 * @method static self country_id()
 * @method static self state_id()
// * @method static self network_id()
 * @method static self network_brand()
 * @method static self date_created()
 * @method static self last_sent()
 * @method static self last_clicked()
 * @method static self leads_count()
 * @method static self sales_count()
 * @method static self custom1_str()
 * @method static self custom2_str()
 * @method static self custom3_str()
 * @method static self custom4_str()
 * @method static self custom5_str()
 * @method static self custom1_dec()
 * @method static self custom2_dec()
 * @method static self custom1_datetime()
 * @method static self custom2_datetime()
 * @method static self custom3_datetime()
 * @method static self custom4_datetime()
 * @method static self custom5_datetime()
 * @method static self tags()
 */
class JqBuilderFieldEnum extends Enum
{
    public function toJqRule(array $customFields = []): array
    {
        return match ($this->label) {
            self::clicked_count()->label => [
                'field' => $this->label,
                'label' => 'Clicked Count',
            ],
            self::sent_count()->label => [
                'field' => $this->label,
                'label' => 'Sent Count',
            ],
            self::country_id()->label => [
                'field' => $this->label,
                'label' => 'Country',
            ],
            self::state_id()->label => [
                'field' => $this->label,
                'label' => 'State',
            ],
//            self::network_id()->label => [
//                'field' => $this->label,
//                'label' => 'Network',
//            ],
            self::network_brand()->label => [
                'field' => $this->label,
                'label' => 'Network Brand',
            ],
            self::date_created()->label => [
                'field' => $this->label,
                'label' => 'Date Created',
            ],
            self::last_sent()->label => [
                'field' => $this->label,
                'label' => 'Last Sent',
            ],
            self::last_clicked()->label => [
                'field' => $this->label,
                'label' => 'Last Clicked',
            ],
            self::leads_count()->label => [
                'field' => $this->label,
                'label' => 'Leads Count',
            ],
            self::sales_count()->label => [
                'field' => $this->label,
                'label' => 'Sales Count',
            ],
            self::custom1_str()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 1 String',
            ],
            self::custom2_str()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 2 String',
            ],
            self::custom3_str()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 3 String',
            ],
            self::custom4_str()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 4 String',
            ],
            self::custom5_str()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 5 String',
            ],
            self::custom1_dec()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 1 Decimal',
            ],
            self::custom2_dec()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 2 Decimal',
            ],
            self::custom1_datetime()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 1 Datetime',
            ],
            self::custom2_datetime()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 2 Datetime',
            ],
            self::custom3_datetime()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 3 Datetime',
            ],
            self::custom4_datetime()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 4 Datetime',
            ],
            self::custom5_datetime()->label => [
                'field' => $this->label,
                'label' => $customFields[$this->label] ?? 'Custom 5 Datetime',
            ],
            self::tags()->label => [
                'field' => $this->label,
                'label' => 'Tags',
            ],
        };
    }
}
