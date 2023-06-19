<?php

namespace App\Jobs;

use App\Data\buildSmsData;
use App\Models\SmsCampaign;
use App\Models\SmsCampaignSend;
use App\Services\SendingProcess\Data\BuildSmsData as SPBuildSmsData;
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

    public function __construct(public buildSmsData $dto)
    {}

    public function handle(): void
    {
        //check stop conditions
        if (SmsCampaignSend::find($this->dto->sms_campaign_send_id)->status == 'stopped') {
            return;
        }

        $res = Cache::pull($this->dto->phone_normalized . '_' . $this->dto->team_id);
        if ($res) {
            \Log::warning('Duplicate sms cache: ' . $this->dto->phone_normalized . '_' . $this->dto->team_id);
            return;
        }

        Cache::put($this->dto->phone_normalized . '_' . $this->dto->team_id, true, 20);

        $data = new BuildSmsData();
        $data->dto = $this->dto;
        $data->sms_id = \Str::uuid()->toString();
        TextService::processMsg($data);
        //decide on route.

        //deduct balance

        //submit to sms build queue
    }
}
