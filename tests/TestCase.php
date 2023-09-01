<?php

namespace Tests;

use App\Services\ClickhouseService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use ClickHouseDB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

//    use DatabaseMigrations;


//    if adding refresh database in other tables add this clickhouse drop all tables..
//    use RefreshDatabase;

//
//    protected function beforeRefreshingDatabase()
//    {
//        ClickhouseService::dropAllTables();
//    }

    protected $rabbitmmqConnection;
    protected $clickhouse;
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
//        $this->artisan('migrate'); 
        $this->rabbitmmqConnection = new AMQPStreamConnection(
          env('RABBITMQ_HOST'), 
          env('RABBITMQ_PORT'), 
          env('RABBITMQ_USERNAME'), 
          env('RABBITMQ_PASSWORD')
        );

        $this->clickhouse = new ClickHouseDB\Client([
            'host' => env('CLICKHOUSE_HOST'),
            'port' => env('CLICKHOUSE_PORT'),
            'username' => env('CLICKHOUSE_USERNAME'),
            'password' => env('CLICKHOUSE_PASSWORD')
        ]);
        $this->clickhouse->database(env('CLICKHOUSE_DATABASE'));        
    }
}
