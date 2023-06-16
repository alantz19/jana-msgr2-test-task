<?php

namespace Database\Factories;

use App\Models\DataFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataFileFactory extends Factory
{
    protected $model = DataFile::class;

    public function definition(): array
    {
        return [
            'type' => DataFile::TYPE_NUMBERS_FILE,
            'name' => $this->faker->word,
            'path' => $this->faker->filePath(),
            'size' => $this->faker->randomNumber(),
            'meta' => [
                'list_name' => $this->faker->word,
                'columns' => [
                    'number' => 0,
                    'country' => 1,
                ],
            ],
            'created_at' => Carbon::now(),
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

    public function withFile($filePath): static
    {
        $info = pathinfo($filePath);

        return $this->state(function (array $attributes) use ($filePath, $info) {
            return [
                'name' => $info['basename'],
                'path' => str_replace(storage_path('app/'), '', $filePath),
                'size' => filesize($filePath),
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
