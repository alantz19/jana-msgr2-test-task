<?php

namespace Database\Factories;

use App\Models\SmsRoute;
use App\Models\SmsRouteCompany;
use App\Models\SmsRouteSmppConnection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class SmsRouteFactory extends Factory
{
    protected $model = SmsRoute::class;

    public function definition(): array
    {
        return [
            'name' => 'Route ' . rand(0, 99999),
            'sms_route_company_id' => SmsRouteCompany::factory()
                ->state(['team_id' => Str::uuid()->toString()])
                ->create()->id,
        ];
    }

    public function withSmppConnection()
    {
        return $this->has(SmsRouteSmppConnection::factory(), 'smppConnection');
    }
}
