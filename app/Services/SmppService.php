<?php

namespace App\Services;

use App\Data\SmppConnectionData;

class SmppService
{
    public static function testConnection(SmppConnectionData $data)
    {
        return true; //todo when implementing smpp
    }
}
