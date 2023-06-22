<?php

namespace Database\Factories;

use App\Enums\SegmentStatusEnum;
use App\Enums\SegmentTypeEnum;
use App\Models\Segment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SegmentFactory extends Factory
{
    protected $model = Segment::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'status_id' => SegmentStatusEnum::active()->value,
        ];
    }

    public function withUser(User $user): static
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    public function withNumbersSample1(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => SegmentTypeEnum::numbers()->value,
                'meta' => [
                    'query' => [
                        'condition' => 'AND',
                        'rules' => [
                            [
                                'id' => 'number_clicks_count',
                                'field' => 'number_clicks_count',
                                'type' => 'integer',
                                'input' => 'number',
                                'operator' => 'equal',
                                'value' => 0,
                            ],
                            [
                                'id' => 'number_country',
                                'field' => 'number_country',
                                'type' => 'integer',
                                'input' => 'select',
                                'operator' => 'equal',
                                'value' => 13,
                            ],
                            [
                                'condition' => 'OR',
                                'rules' => [
                                    [
                                        'id' => 'number_network_id',
                                        'field' => 'number_network_id',
                                        'type' => 'integer',
                                        'input' => 'number',
                                        'operator' => 'equal',
                                        'value' => 1,
                                    ],
                                    [
                                        'id' => 'number_network_brand',
                                        'field' => 'number_network_brand',
                                        'type' => 'string',
                                        'input' => 'text',
                                        'operator' => 'equal',
                                        'value' => 'AT&T',
                                    ],
                                ],
                            ],
                            [
                                'id' => 'number_date_created',
                                'field' => 'number_date_created',
                                'type' => 'date',
                                'input' => 'text',
                                'operator' => 'equal',
                                'value' => '2021/06/22',
                            ],
                        ],
                    ],
                ],
            ];
        });
    }
}
