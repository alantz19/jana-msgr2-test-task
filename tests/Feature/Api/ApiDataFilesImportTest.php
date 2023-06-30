<?php

namespace Tests\Feature\Api;

use App\Jobs\DataFileImportJob;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiDataFilesImportTest extends TestCase
{
    use WithFaker;

    public function test_api_upload_file()
    {
        $res = $this->uploadFileAsSanctumUser();

        $res->assertStatus(201);
    }

    public function test_data_policy()
    {
        $res = $this->uploadFileAsSanctumUser();
        $data = $res->json();

        $user2 = User::factory()->withPersonalTeam()->create();
        Sanctum::actingAs(
            $user2,
            ['*']
        );

        $res = $this->getJson('/api/v1/data-files/' . $data['id'] . '/sample');
        $res->assertStatus(403);
    }

    public function test_api_get_sample()
    {
        $res = $this->uploadFileAsSanctumUser();
        $data = $res->json();

        $sample = $this->getJson('/api/v1/data-files/' . $data['id'] . '/sample');
        $sample->assertStatus(200);
        $data = $sample->json();

        $this->assertEquals(11, $data['cols']);
        $this->assertCount(15, $data['rows']);
    }

    public function test_cannot_start_import_wrong_columns()
    {
        $res = $this->uploadFileAsSanctumUser();
        $data = $res->json();

        $res = $this->postJson('/api/v1/data-files/' . $data['id'] . '/import', [
            'columns' => [
                'number' => 0,
                'country' => 0,
            ],
            'tag' => [$this->faker->word],
        ]);

        $res->assertStatus(422);
    }

    public function test_start_import_valid_columns()
    {
        Queue::fake();
        Queue::assertNothingPushed();

        $res = $this->uploadFileAsSanctumUser();
        $data = $res->json();

        $res = $this->postJson('/api/v1/data-files/' . $data['id'] . '/import', [
            'columns' => [
                'number' => 0,
                'country' => 1,
            ],
            'tags' => [$this->faker->word],
        ]);

        $res->assertStatus(200);

        Queue::assertPushed(DataFileImportJob::class);
    }

    private function uploadFileAsSanctumUser(): TestResponse
    {
        $user = User::factory()->withPersonalTeam()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $path = dirname(__DIR__) . '/data/demo_list-custom-fields.csv';

        return $this->postJson(
            '/api/v1/data-files/contacts/upload-file', [
                'file' => new UploadedFile($path, 'demo_list-custom-fields.csv', 'text/csv', null, true),
            ]
        );
    }
}
