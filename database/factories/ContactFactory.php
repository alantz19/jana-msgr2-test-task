<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'team_id' => $this->faker->uuid,
            'name' => $this->faker->name,
            'list_id' => $this->faker->uuid,
            'phone_normalized' => $this->faker->phoneNumber,
            'phone_is_good' => $this->faker->boolean,
            'phone_is_good_reason' => $this->faker->randomNumber(1, 5),
            'country_id' => $this->faker->uuid,
            'state_id' => $this->faker->uuid,
        ];
    }
}
