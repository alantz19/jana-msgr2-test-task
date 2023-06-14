<?php

namespace Tests\Feature;

use App\Imports\NumbersImport;
use App\Models\DataFile;
use Tests\TestCase;

class ImportFileTest extends TestCase
{
    private string $csvFile;

    public function testNumbersImport()
    {
        $path = storage_path('app/data/demo_list-100.xlsx');
        $this->assertFileExists($path);

        $dataFile = DataFile::factory()->withFile($path)->create();

//        Excel::fake();

        $import = new NumbersImport($dataFile->meta['columns']);
        $import->import($path);

        dump($import->errors()->toArray());
        dump($import->failures()->toArray());
//        Excel::assertImported($path);
    }
}