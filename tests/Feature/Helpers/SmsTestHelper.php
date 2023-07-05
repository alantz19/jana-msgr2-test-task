<?php

namespace Tests\Feature\Helpers;

use App\Services\ClickhouseService;

class SmsTestHelper
{

    public static function generateClicks($limitQuery, $perc)
    {
        $smss = ClickhouseService::query("
        select * from sms_sendlogs_v where $limitQuery;");

        $insertClicks = [];
        foreach ($smss as $sms) {
            $insertClicks[] = [
                'sms_id' => $sms['sms_id'],
                'is_clicked' => rand(0, 99) < $perc ? 1 : 0,
                'updated_datetime' => microtime(true),
            ];
        }
        ClickhouseService::getClient()->insertAssocBulk('sms_sendlogs', $insertClicks);
    }
}