<?php

namespace App\Jobs;

use App\Dto\buildSmsDto;
use App\Models\SmsCampaign;
use App\Models\SmsCampaignSend;
use App\Services\SendingProcess\Data\BuildSmsData;
use App\Services\SendingProcess\TextService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class buildSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(buildSmsDto $dto): void
    {
        //check stop conditions
        if (SmsCampaignSend::find($dto->campaign_send_id)->status == 'stopped') {
            return;
        }

        $res = Cache::pull($dto->phone_normalized . '_' . $dto->team_id);
        if ($res) {
            \Log::warning('Duplicate sms cache: ' . $dto->phone_normalized . '_' . $dto->team_id);
            return;
        }

        Cache::put($dto->phone_normalized . '_' . $dto->team_id, true, 20);

        $data = new BuildSmsData();
        $data->dto = $dto;
        $data->sms_id = \Str::uuid()->toString();
        TextService::processMsg($data);
        //decide on route.

        //deduct balance

        //submit to sms build queue
    }
}
