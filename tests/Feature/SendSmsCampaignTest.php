<?php

namespace Tests\Feature\Domain\Sms\Sends\Campaigns;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\CompanyUser;
use App\Models\User;
use Faker\Core\Uuid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use Tests\TestCase;

class SendSmsCampaignTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        /** @var CompanyUser $user */
        $this->actingAs($user);
    }

    public function test_create_campaign()
    {

    }
}
