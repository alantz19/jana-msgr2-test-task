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
        $path = __DIR__ . '/data/demo_list-100.xlsx';
        $this->assertFileExists($path);

        $dataFile = DataFile::factory()
            ->withFile($path)
            ->create();

        $import = new NumbersImport($dataFile);
        $import->import();

        // @TODO check imported data
    }
}