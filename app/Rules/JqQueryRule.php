<?php

namespace App\Rules;

use App\Enums\JqBuilderFieldEnum;
use App\Enums\JqBuilderOperatorEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class JqQueryRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value)) {
            $fail('The rules item must be a valid rule.');
        }

        if (empty($value['id']) || !in_array($value['id'], JqBuilderFieldEnum::toValues())) {
            $fail('The rule id must be a valid id.');
        }

        if (empty($value['field']) || !in_array($value['field'], JqBuilderFieldEnum::toValues())) {
            $fail('The rule field must be a valid field.');
        }

        if (empty($value['operator']) || !in_array($value['operator'], JqBuilderOperatorEnum::toLabels())) {
            $fail('The rule operator must be a valid operator.');
        }

        $jqBuilderTypes = [
            'string',
            'integer',
            'double',
            'date',
            'time',
            'datetime',
            'boolean',
        ];
        if (empty($value['type']) || !in_array($value['type'], $jqBuilderTypes)) {
            $fail('The rule type must be a valid type.');
        }

        $jqBuilderInputs = [
            'text',
            'number',
            'textarea',
            'radio',
            'checkbox',
            'select',
        ];
        if (empty($value['input']) || !in_array($value['input'], $jqBuilderInputs, true)) {
            $fail('The rule input must be a valid input.');
        }
    }
}
