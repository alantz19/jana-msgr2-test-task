<?php

namespace Database\Factories;

use App\Models\CustomField;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomFieldFactory extends Factory
{
    protected $model = CustomField::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'team_id' => $this->faker->uuid,
            'field_name' => $this->faker->word,
            'field_key' => $this->faker->randomKey([
                'custom1_str',
                'custom2_str',
                'custom3_str',
                'custom4_str',
                'custom5_str',
                'custom1_int',
                'custom2_int',
                'custom3_int',
                'custom4_int',
                'custom5_int',
                'custom1_dec',
                'custom2_dec',
                'custom1_datetime',
                'custom2_datetime',
                'custom3_datetime',
                'custom4_datetime',
                'custom5_datetime',
            ]),
        ];
    }
}
