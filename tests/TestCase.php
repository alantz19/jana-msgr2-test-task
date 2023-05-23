<?php

namespace Tests;

use App\Services\ClickhouseService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    //if adding refresh database in other tables add this clickhouse drop all tables..
    use \Illuminate\Foundation\Testing\RefreshDatabase;
    protected function beforeRefreshingDatabase()
    {
        ClickhouseService::dropAllTables();
    }
}
