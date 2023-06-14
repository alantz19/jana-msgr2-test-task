<?php

namespace Tests\Feature;

use App\Models\DataFile;
use App\Services\DataFileService;
use Tests\TestCase;

class ImportFileTest extends TestCase
{
    private string $csvFile;

    public function testXls2Csv()
    {
        $path = __DIR__ . '/data/demo_list-100.xlsx';;
        $dataFile = DataFile::factory()->withFile($path)->create();

        $this->assertFileExists($path);

        $this->csvFile = DataFileService::xls2csv($dataFile);

        $this->assertFileExists($this->csvFile);
    }
}