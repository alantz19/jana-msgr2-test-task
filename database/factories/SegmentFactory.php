<?php

namespace Database\Factories;

use App\Enums\SegmentStatusEnum;
use App\Enums\SegmentTypeEnum;
use App\Models\Segment;
use Illuminate\Database\Eloquent\Factories\Factory;

class SegmentFactory extends Factory
{
    protected $model = Segment::class;

    public function definition(): array
    {
        return [
            'team_id' => $this->faker->uuid,
            'name' => $this->faker->name,
            'status_id' => SegmentStatusEnum::active()->value,
        ];
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
                                'field' => 'clicked_count',
                                'operator' => 'greater_or_equal',
                                'value' => 0,
                            ],
                            [
                                'field' => 'country_id',
                                'operator' => 'equal',
                                'value' => 225,
                            ],
                            [
                                'field' => 'date_created',
                                'operator' => 'equal',
                                'value' => now()->toDateString(),
                            ],
                        ],
                    ],
                ],
            ];
        });
    }

    public function withNumbersSample2(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => SegmentTypeEnum::numbers()->value,
                'meta' => [
                    'query' => [
                        'condition' => 'AND',
                        'rules' => [
                            [
                                'field' => 'clicked_count',
                                'operator' => 'greater',
                                'value' => 0,
                            ],
                            [
                                'field' => 'country_id',
                                'operator' => 'equal',
                                'value' => 225,
                            ],
                            [
                                'condition' => 'OR',
                                'rules' => [
                                    [
                                        'field' => 'leads_count',
                                        'operator' => 'equal',
                                        'value' => 1,
                                    ],
                                    [
                                        'field' => 'sales_count',
                                        'operator' => 'equal',
                                        'value' => 1,
                                    ],
                                ],
                            ],
                            [
                                'field' => 'date_created',
                                'operator' => 'equal',
                                'value' => now()->toDateString(),
                            ],
                        ],
                    ],
                ],
            ];
        });
    }

    public function withNumbersSample3(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => SegmentTypeEnum::numbers()->value,
                'meta' => [
                    'query' => [
                        'condition' => 'AND',
                        'rules' => [
                            [
                                'field' => 'clicked_count',
                                'operator' => 'greater',
                                'value' => 0,
                            ],
                            [
                                'field' => 'tags',
                                'operator' => 'in',
                                'value' => [
                                    'user-tag-1',
                                    'user-tag-2',
                                ],
                            ],
                            [
                                'field' => 'tags',
                                'operator' => 'begins_with',
                                'value' => 'user',
                            ],
                        ],
                    ],
                ],
            ];
        });
    }
}
