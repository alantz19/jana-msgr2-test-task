<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use PhpClickHouseLaravel\RawColumn;
use Tests\TestCase;

class ContactsTest extends TestCase
{
    public function testContactFactory()
    {
        $list_id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $contacts = Contact::factory()->saveAndReturn($list_id);
        $count = Contact::select(new RawColumn('count() as count'))->where('list_id', $list_id)->groupBy('list_id')
            ->get();
        $this->assertEquals("100", $count->fetchOne()['count']);
        $this->assertEquals(100, $contacts->count());
    }
}
