<?php

namespace Tests\Feature;

use App\Enums\DataFileTypeEnum;
use App\Imports\NumbersFileImport;
use App\Jobs\DataFileImportJob;
use App\Models\Contact;
use App\Models\DataFile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use PhpClickHouseLaravel\RawColumn;
use Tests\Feature\Api\BaseApiTest;
use Tests\TestCase;

class ImportFileTest extends BaseApiTest
{
    public function testNumbersImportXlsx()
    {
        $path = __DIR__ . '/data/demo_list-20.xlsx';

        // copy to data-files storage directory
        $targetPath = storage_path('app/users/' . $this->user->id . '/data-files');
        $targetFile = $targetPath . '/demo_list-20.xlsx';
        File::makeDirectory($targetPath, 0775, true, true);
        File::copy($path, $targetFile);

        $dataFile = DataFile::factory()
            ->withUser($this->user)
            ->withFile($targetFile)
            ->withLogicalTest()
            ->create();

        $import = new NumbersFileImport($dataFile);
        $import->import();

        $data = Contact::select(new RawColumn('uniqExact(id)', 'total'))
            ->where('list_id', $import->getList()->id)
            ->get()
            ->fetchOne();

        $this->assertEquals(20, (int)$data['total']);
    }

    public function testNumbersImportCustomFields()
    {
        $path = __DIR__ . '/data/demo_list-custom-fields.csv';

        // copy to data-files storage directory
        $targetPath = storage_path('app/users/' . $this->user->id . '/data-files');
        $targetFile = $targetPath . '/demo_list-custom-fields.csv';
        File::makeDirectory($targetPath, 0775, true, true);
        File::copy($path, $targetFile);

        $dataFile = DataFile::factory()
            ->withUser($this->user)
            ->withFile($targetFile)
            ->withLogicalTest()
            ->withCustomColumns()
            ->create();

        $import = new NumbersFileImport($dataFile);
        $import->import();

        $data = Contact::select(new RawColumn('uniqExact(id)', 'total'))
            ->where('list_id', $import->getList()->id)
            ->get()
            ->fetchOne();

        $this->assertEquals(20, (int)$data['total']);

        $rows = Contact::where('list_id', $import->getList()->id)->getRows();

        foreach ($rows as $row) {
            $this->assertNotEmpty($row['custom1_str']);
//            $this->assertNotEmpty($row['custom2_str']);
            $this->assertNotEmpty($row['custom1_int']);
//            $this->assertNotEmpty($row['custom2_int']);
            $this->assertNotEmpty($row['custom1_dec']);
//            $this->assertNotEmpty($row['custom2_dec']);
            $this->assertNotEmpty($row['custom1_datetime']);
//            $this->assertNotEmpty($row['custom2_datetime']);
        }
    }

    public function testAutoDetectDelimiter()
    {
        $path = __DIR__ . '/data/demo_list-auto-detect-delimiter.csv';

        // copy to data-files storage directory
        $targetPath = storage_path('app/users/' . $this->user->id . '/data-files');
        $targetFile = $targetPath . '/demo_list-auto-detect-delimiter.csv';
        File::makeDirectory($targetPath, 0775, true, true);
        File::copy($path, $targetFile);

        $dataFile = DataFile::factory()
            ->withUser($this->user)
            ->withFile($targetFile)
            ->withLogicalTest()
            ->create();

        $import = new NumbersFileImport($dataFile);

        $this->assertEquals(';', $import->getDelimiter());

        $import->import();

        $data = Contact::select(new RawColumn('uniqExact(id)', 'total'))
            ->where('list_id', $import->getList()->id)
            ->get()
            ->fetchOne();

        $this->assertEquals(20, (int)$data['total']);
    }

    public function testUnauthorizedRequest()
    {
        $this->actingAsGuest();
        $path = __DIR__ . '/data/demo_list-custom-fields.csv';

        $res = $this->postJson(
            '/api/v1/data-files/contacts', [
                'type' => DataFileTypeEnum::numbers()->label,
                'file' => new UploadedFile($path, 'demo_list-custom-fields.csv', 'text/csv', null, true),
            ]
        );

        $res->assertStatus(401);
    }

    public function testApiUploadFile()
    {
        $this->actingAs($this->user);
        $path = __DIR__ . '/data/demo_list-custom-fields.csv';
        $res = $this->postJson(
            '/api/v1/data-files/contacts', [
                'type' => DataFileTypeEnum::numbers()->label,
                'file' => new UploadedFile($path, 'demo_list-custom-fields.csv', 'text/csv', null, true),
            ]
        );

        $res->assertStatus(201);

        return $res->json();
    }

    /**
     * @depends testApiUploadFile
     */
    public function testDataFilePolicy($data)
    {
        $user = User::factory()
            ->withPersonalTeam()
            ->withSanctumToken()
            ->create();
        $this->actingAs($user);

        $res = $this->getJson('/api/v1/data-files/' . $data['id'] . '/sample');
        $res->assertStatus(404);
    }

    /**
     * @depends testApiUploadFile
     */
    public function testApiGetSample($data)
    {
        $this->actingAs($this->user);
        $res = $this->getJson('/api/v1/data-files/' . $data['id'] . '/sample');
        $res->assertStatus(200);
        $sample = $res->json();

        $this->assertEquals(11, $sample['cols']);
        $this->assertCount(15, $sample['rows']);

        return [
            'id' => $data['id'],
            'sample' => $sample,
        ];
    }

    /**
     * @depends testApiGetSample
     */
    public function testStartImportWrongData($data)
    {
        $res = $this->postJson('/api/v1/data-files/' . $data['id'] . '/import', [
            'columns' => [
                'number' => 0,
                'country' => 0,
            ],
            'list_name' => 'test list',
        ]);

        $res->assertStatus(422);

        return $data;
    }

    /**
     * @depends testApiGetSample
     */
    public function testStartImport($data)
    {
        Queue::fake();
        Queue::assertNothingPushed();

        $res = $this->postJson('/api/v1/data-files/' . $data['id'] . '/import', [
            'columns' => [
                'number' => 0,
                'country' => 1,
            ],
            'list_name' => 'test list',
        ]);

        $res->assertStatus(200);

        Queue::assertPushedOn('data_file_import', DataFileImportJob::class);
    }

    private function createTestUser(): void
    {
        if (empty($this->user)) {
            $this->user = User::factory()
                ->withPersonalTeam()
                ->withSanctumToken()
                ->create();
        }
    }
}
