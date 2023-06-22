<?php

namespace Database\Factories\Clickhouse;

use App\Models\Clickhouse\Contact;
use App\Models\WorldMobileNetwork;
use App\Services\ClickhouseService;
use App\Services\CountryService;
use App\Services\SmsContactMobileNetworksService;
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

    public function saveAndReturn($list_id = false, $country = 'uk', $withNetworks = false):Collection
    {
        $contacts = new Collection();
        if (!$list_id) {
            $list_id = \Ramsey\Uuid\Uuid::uuid4()->toString();//use same list of all contacts..
        }
        $i=0;
        while ($i < 100) {
            $i++;
            $contact = new Contact();
            $contact->fill($this->definition());
            $contact->list_id = $list_id;
            $contact->country_id = CountryService::guessCountry($country);
            $contacts->add($contact);
        }
        ClickhouseService::batchInsertModelCollection($contacts);

        if ($withNetworks) {
            foreach ($contacts as $contact) {
                //todo:add test
                $network_id = SmsContactMobileNetworksService::getNetworkCacheForNumber($contact->phone_normalized);
                if (!$network_id) { //random network
                    $network_id = WorldMobileNetwork::where(['world_country_id' => $contact->country_id])
                        ->whereNotNull('brand')
                        ->whereNot('brand', '')
                        ->inRandomOrder()->first()->id;
                    SmsContactMobileNetworksService::saveNumberNetwork($contact->phone_normalized, $network_id);
                }
                $contact->network_id = $network_id;
            }
        }

        return $contacts;
    }
}
