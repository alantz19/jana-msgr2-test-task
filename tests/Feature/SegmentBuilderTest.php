<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Segment;
use App\Models\Team;
use App\Services\SegmentBuilderService;
use Tests\TestCase;

class SegmentBuilderTest extends TestCase
{
    private static Team $team;

    public function setUp(): void
    {
        parent::setUp();

        if (empty(self::$team)) {
            self::$team = Team::factory()->create();
        }
    }

    public function test_segment_builder_sample1()
    {
        $segment = Segment::factory()
            ->withTeam(self::$team)
            ->withNumbersSample1()
            ->create();

        $builder = SegmentBuilderService::create($segment);
        $sql = $builder->toSql();

        $this->assertStringStartsWith('SELECT * FROM', $sql);
        $this->assertStringContainsString('greaterOrEquals(clicked_count, 0)', $sql);
        $this->assertStringContainsString('equals(country_id, 225)', $sql);

        Contact::factory()
            ->withTeam(self::$team)
            ->saveAndReturn();

        $rows = $builder->getRows();

        $this->assertCount(100, $rows);
    }
}
