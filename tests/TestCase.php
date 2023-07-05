<?php

namespace Tests;

use App\Services\ClickhouseService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;


//    if adding refresh database in other tables add this clickhouse drop all tables..
//    use RefreshDatabase;
//
//    protected function beforeRefreshingDatabase()
//    {
//        ClickhouseService::dropAllTables();
//    }
}
