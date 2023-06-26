<?php

namespace Database\Factories;

use App\Models\DataFile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataFileFactory extends Factory
{
    protected $model = DataFile::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'file_name' => $this->faker->word . '.csv',
            'file_size' => $this->faker->randomNumber(),
            'meta' => [
                'columns' => [
                    'number' => 0,
                    'country' => 1,
                ],
            ],
            'created_at' => Carbon::now(),
        ];
    }

    public function withTeamId(string $teamId): static
    {
        return $this->state(function (array $attributes) use ($teamId) {
            return [
                'team_id' => $teamId,
            ];
        });
    }

    public function withFileName($fileName): static
    {
        return $this->state(function (array $attributes) use ($fileName) {
            return [
                'name' => $fileName,
                'file_name' => $fileName,
                'size' => $this->faker->randomNumber(),
            ];
        });
    }

    public function withLogicalTest(): static
    {
        return $this->state(function (array $attributes) {
            $attributes['meta']['logical_test'] = true;
            return $attributes;
        });
    }

    public function withCustomColumns(): static
    {
        return $this->state(function (array $attributes) {
            $attributes['meta']['columns'] = array_merge(
                $attributes['meta']['columns'],
                [
                    'custom1_str' => 2,
                    'custom2_str' => 3,
                    'custom1_int' => 4,
                    'custom2_int' => 5,
                    'custom1_dec' => 6,
                    'custom2_dec' => 7,
                    'custom1_datetime' => 8,
                    'custom2_datetime' => 9,
                ]
            );
            return $attributes;
        });
    }
}
