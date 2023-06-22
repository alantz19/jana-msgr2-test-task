<?php

namespace Tests\Feature;

use App\Models\Segment;
use App\Models\User;
use App\Services\SegmentBuilderService;
use Tests\TestCase;

class SegmentBuilderTest extends TestCase
{
    private static User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->createTestUser();
    }

    public function test_builder()
    {
        $segment = Segment::factory()
            ->withUser(self::$user)
            ->withNumbersSample1()
            ->create();

        $query = SegmentBuilderService::buildQuery($segment);
        dd($query->toSql());
    }

    private function createTestUser(): void
    {
        self::$user = User::factory()->create();
    }
}
