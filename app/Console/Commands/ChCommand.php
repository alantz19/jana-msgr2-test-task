<?php

namespace App\Console\Commands;

use ClickHouseDB\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ChCommand extends Command
{
    protected $signature = 'ch:fresh';

    protected $description = 'Command description';

    public function handle(): void
    {
        /** @var Client $db */
        $db = DB::connection('clickhouse')->getClient();
        $tables = $db->showTables();
        foreach ($tables as $table) {
            $db->write('DROP TABLE `'.$table['name'].'`');
        }
        Artisan::call('migrate:fresh');
    }
}
