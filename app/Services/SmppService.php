<?php

namespace App\Services;

use App\Http\Requests\SmsRouteSmppCreateRequest;
use App\Models\SmsRouteSmppConnection;

class SmppService
{
    public static function testConnection(SmsRouteSmppConnection $data): bool
    {
        return true; //todo when implementing smpp
    }
}
