<?php

namespace Tests\Feature\Api;

use App\Models\Segment;

class ApiSegmentsTest extends BaseApiTest
{
    public function test_index_filter_type()
    {
        $segment = $this->createSegmentFactory();
        $res = $this->getJson('/api/v1/segments')->assertOk();
        $data = $res->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals($segment->id, $data[0]['id']);

        $res = $this->getJson('/api/v1/segments?type=emails')->assertOk();
        $data = $res->json('data');
        $this->assertCount(0, $data);
    }

    private function createSegmentFactory(): Segment
    {
        $teamId = $this->user->current_team_id;
        return Segment::factory()
            ->state(function (array $attributes) use ($teamId) {
                return [
                    'team_id' => $teamId,
                ];
            })
            ->withNumbersSample1()
            ->create();
    }
}
