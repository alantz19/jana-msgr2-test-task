<?php

namespace Database\Factories\Clickhouse;

use App\Models\Clickhouse\Contact;
use App\Models\MobileNetwork;
use App\Models\Team;
use App\Services\ClickhouseService;
use App\Services\CountryService;
use App\Services\SmsContactMobileNetworksService;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class ContactFactory extends Factory
{
    public function saveAndReturn($country = 'uk', $withNetworks = false): Collection
    {
        $contacts = new Collection();
//        if (!$list_id) {
//            $list_id = Uuid::uuid4()->toString();//use same list of all contacts..
//        }

        $teamId = Uuid::uuid4()->toString();

        $i = 0;
        while ($i < 100) {
            $i++;
            $contact = new Contact();
            $contact->fill($this->definition());
//            $contact->list_id = $list_id;
            $contact->team_id = $teamId;
            $contact->country_id = CountryService::guessCountry($country);

            if (!empty(self::$team)) {
                $contact->team_id = self::$team->id;
            }

            $contacts->add($contact);
        }
        ClickhouseService::batchInsertModelCollection($contacts);

        if ($withNetworks) {
            foreach ($contacts as $contact) {
                //todo:add test
                $network_id = SmsContactMobileNetworksService::getNetworkCacheForNumber($contact->phone_normalized);
                if (!$network_id) { //random network
                    $network_id = MobileNetwork::where(['country_id' => $contact->country_id])
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

    public function definition(): array
    {
        return [
            'contact_id' => $this->faker->uuid,
            'team_id' => $this->faker->uuid,
            'name' => $this->faker->name,
//            'list_id' => $this->faker->uuid,
            'phone_normalized' => str_replace('+', '', $this->faker->e164PhoneNumber()),
            'phone_is_good' => true,
            'phone_is_good_reason' => $this->faker->randomNumber(1, 5),
            'country_id' => 225,
//            'date_created' => now()->toDateTimeString(),
//            'state_id' => 1,
            'date_created' => now()->toDateTime(),
        ];
    }

    public function withTeam(Team $team): static
    {
        self::$team = $team;

        return $this->state(function (array $attributes) use ($team) {
            return [
                'team_id' => $team->id,
            ];
        });
    }
}
