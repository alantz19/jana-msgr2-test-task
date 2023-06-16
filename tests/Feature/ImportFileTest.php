<?php

namespace Tests\Feature;

use App\Imports\NumbersFileImport;
use App\Models\Contact;
use App\Models\DataFile;
use App\Models\User;
use Illuminate\Support\Facades\File;
use PhpClickHouseLaravel\RawColumn;
use Tests\TestCase;

class ImportFileTest extends TestCase
{
    public function testNumbersImportXlsx()
    {
        $path = __DIR__ . '/data/demo_list-100.xlsx';
        $user = User::factory()->withPersonalTeam()->create();

        // copy to data-files storage directory
        $targetPath = storage_path('app/users/' . $user->id . '/data-files');
        $targetFile = $targetPath . '/demo_list-100.xlsx';
        File::makeDirectory($targetPath, 0775, true, true);
        File::copy($path, $targetFile);

        $dataFile = DataFile::factory()
            ->withUser($user)
            ->withFile($targetFile)
            ->withLogicalTest()
            ->create();

        $import = new NumbersFileImport($dataFile);
        $import->import();

        $data = Contact::select(new RawColumn('uniqExact(id)', 'total'))
            ->where('list_id', $import->getList()->id)
            ->get()
            ->fetchOne();

        $this->assertEquals(100, (int) $data['total']);
    }

    public function testNumbersImportCustomFields()
    {
        $path = __DIR__ . '/data/demo_list-custom-fields.csv';
        $user = User::factory()->withPersonalTeam()->create();

        // copy to data-files storage directory
        $targetPath = storage_path('app/users/' . $user->id . '/data-files');
        $targetFile = $targetPath . '/demo_list-custom-fields.csv';
        File::makeDirectory($targetPath, 0775, true, true);
        File::copy($path, $targetFile);

        $dataFile = DataFile::factory()
            ->withUser($user)
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

        $this->assertEquals(20, (int) $data['total']);

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
}
