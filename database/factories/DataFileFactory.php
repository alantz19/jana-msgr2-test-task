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
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'type' => DataFile::TYPE_SMS_IMPORT,
            'name' => $this->faker->word,
            'path' => $this->faker->filePath(),
            'size' => $this->faker->randomNumber(),
            'meta' => [
                'columns' => [
                    'numberCol' => 0,
                    'countryCol' => 1,
                ],
            ],
            'created_at' => Carbon::now(),
        ];
    }

    public function withFile($filePath): static
    {
        $info = pathinfo($filePath);

        return $this->state(function (array $attributes) use ($filePath, $info) {
            return [
                'name' => $info['basename'],
                'path' => $filePath,
                'size' => filesize($filePath),
            ];
        });
    }
}
