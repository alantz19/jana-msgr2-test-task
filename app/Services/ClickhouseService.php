<?php

namespace App\Services;

use ClickHouseDB\Client;
use Illuminate\Support\Facades\DB;

class ClickhouseService
{
    public static function dropAllTables()
    {
        /** @var Client $db */
        $db = DB::connection('clickhouse')->getClient();
        $tables = $db->showTables();
        foreach ($tables as $table) {
            $db->write('DROP TABLE `'.$table['name'].'`');
        }
    }

    public static function getClient(): Client
    {
        return DB::connection('clickhouse')->getClient();
    }
}
