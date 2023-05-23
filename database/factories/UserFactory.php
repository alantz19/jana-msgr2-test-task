<?php

namespace Database\Factories;

use App\Models\SmsRoute;
use App\Models\SmsRoutePlatformConnection;
use App\Models\SmsRouteRate;
use App\Models\SmsRoutingPlan;
use App\Models\Team;
use App\Models\User;
use App\Services\CountryService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(callable $callback = null): static
    {
        return $this->has(
            Team::factory()
                ->state(fn(array $attributes, User $user) => [
                    'name' => $user->name . '\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])->afterCreating(
                    function (Team $team, User $user) {
                        $user->forceFill(['current_team_id' => $team->id])->save();
//                        if ($this->withUkPlatformRoute()) {
//                            $plan = SmsRoutingPlan::factory()->create([
//                                'team_id' => $team->id,
//                                'name' => 'UK Platform Route',
//                            ]);
//                            $team->routingPlans()->create(
//                                [
//                                    'name' => 'UK Platform Route',
//                                    'is_active' => true,
//                                ]
//                            );
//                        }
                    }
                ),
        );
    }

    public function withUkPlatformRoute(callable $callback = null): static
    {
//        $sellerTeam = Team::factory()
//            ->has(
//                SmsRoutingPlan::factory()
//                    ->has(SmsRoute::factory()
//                        ->has(SmsRouteRate::factory()->state([
//                            'country_id' => CountryService::guessCountry('UK'),
//                            'rate' => '0.01',
//                        ]))
//                    )
//            );
//
//        create();
//        return $this->has(
//            SmsRoutingPlan::factory()
//                ->state(fn(array $attributes, User $user) => [
//                    'name' => 'UK Platform Route',
//                    'team_id' => $sellerTeam->id,
//                ])
//                ->afterCreating(function (SmsRoutingPlan $plan, User $user) use ($sellerTeam) {
//                    SmsRoutePlatformConnection::create([
//                        'plan_id' => $plan->id,
//                        'customer_team_id' => $user->currentTeam->id,
//                    ]);
//
//                    $plan->hasMany(SmsRoute::factory()->state([
//                        'name' => 'route 123',
//                        'is_active' => true,
//                    ]));
//                }),
//        );
        return $this;
    }
}
