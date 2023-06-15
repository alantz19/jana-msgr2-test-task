<?php

namespace Tests\Feature;

use App\Imports\NumbersImport;
use App\Models\Contact;
use App\Models\DataFile;
use PhpClickHouseLaravel\RawColumn;
use Tests\TestCase;

class ImportFileTest extends TestCase
{
    public function testNumbersImport()
    {
        $path = __DIR__ . '/data/demo_list-100.xlsx';
        $this->assertFileExists($path);

        $dataFile = DataFile::factory()
            ->withFile($path)
            ->withLogicalTest()
            ->create();

        $import = new NumbersImport($dataFile);
        $import->import();

        $data = Contact::select(new RawColumn('uniqExact(id)', 'total'))
            ->where('list_id', $import->getList()->id)
            ->get()
            ->fetchOne();

        $this->assertEquals(100, (int) $data['total']);
    }
}
