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
                                'id' => 'clicked_count',
                                'field' => 'clicked_count',
                                'type' => 'integer',
                                'input' => 'number',
                                'operator' => 'greater_or_equal',
                                'value' => 0,
                            ],
                            [
                                'id' => 'country_id',
                                'field' => 'country_id',
                                'type' => 'integer',
                                'input' => 'select',
                                'operator' => 'equal',
                                'value' => 225,
                            ],
                            [
                                'id' => 'date_created',
                                'field' => 'date_created',
                                'type' => 'date',
                                'input' => 'text',
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
                                'id' => 'clicked_count',
                                'field' => 'clicked_count',
                                'type' => 'integer',
                                'input' => 'number',
                                'operator' => 'greater',
                                'value' => 0,
                            ],
                            [
                                'id' => 'country_id',
                                'field' => 'country_id',
                                'type' => 'integer',
                                'input' => 'select',
                                'operator' => 'equal',
                                'value' => 225,
                            ],
                            [
                                'condition' => 'OR',
                                'rules' => [
                                    [
                                        'id' => 'network_id',
                                        'field' => 'network_id',
                                        'type' => 'integer',
                                        'input' => 'number',
                                        'operator' => 'equal',
                                        'value' => 1,
                                    ],
                                    [
                                        'id' => 'network_brand',
                                        'field' => 'network_brand',
                                        'type' => 'string',
                                        'input' => 'text',
                                        'operator' => 'contains',
                                        'value' => 'AT&T',
                                    ],
                                ],
                            ],
                            [
                                'id' => 'date_created',
                                'field' => 'date_created',
                                'type' => 'date',
                                'input' => 'text',
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
                                'id' => 'clicked_count',
                                'field' => 'clicked_count',
                                'type' => 'integer',
                                'input' => 'number',
                                'operator' => 'greater',
                                'value' => 0,
                            ],
                            [
                                'id' => 'tags',
                                'field' => 'tags',
                                'type' => 'string',
                                'input' => 'select',
                                'operator' => 'in',
                                'value' => 'user-tag-1',
                            ],
                        ],
                    ],
                ],
            ];
        });
    }
}
