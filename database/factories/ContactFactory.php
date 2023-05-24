<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Services\ClickhouseService;
use App\Services\CountryService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'team_id' => $this->faker->uuid,
            'name' => $this->faker->name,
            'list_id' => $this->faker->uuid,
            'phone_normalized' => str_replace('+', '', $this->faker->e164PhoneNumber()),
            'phone_is_good' => true,
            'phone_is_good_reason' => $this->faker->randomNumber(1, 5),
            'country_id' => $this->faker->uuid,
//            'state_id' => 1,
        ];
    }

    public function saveAndReturn($list_id = false):Collection
    {
        $contacts = new Collection();
        if (!$list_id) {
            $list_id = \Ramsey\Uuid\Uuid::uuid4()->toString();//use same list of all contacts..
        }
        $i=0;
        while ($i < 100) {
            $i++;
            $contact = $this->make();
            $contact->list_id = $list_id;
            $contact->country_id = CountryService::guessCountry('uk');
            $contacts->add($contact);
        }
        ClickhouseService::batchInsertModelCollection($contacts);

        return $contacts;
    }
}
