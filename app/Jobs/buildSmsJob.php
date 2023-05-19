<?php

namespace App\Jobs;

use App\Dto\buildSmsDto;
use App\Services\SendingProcess\Data\BuildSmsData;
use App\Services\SendingProcess\TextService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class buildSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(buildSmsDto $dto): void
    {
        $data = new BuildSmsData();
        $data->dto = $dto;
        //check if campaign stopped, check if in temp cache
        //generate sms id
        //finalise sms text (sender id, url, sms text etc')

        TextService::processMsg($data);

        //deduct balance
        //add to cache
        //select route
        //submit to sms build queue
    }
}
