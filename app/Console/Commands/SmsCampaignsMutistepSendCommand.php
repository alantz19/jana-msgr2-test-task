<?php

namespace App\Console\Commands;

use App\Enums\SmsCampaignStatusEnum;
use App\Models\SmsCampaignSend;
use App\Services\SendCampaignService;
use Illuminate\Console\Command;

class SmsCampaignsMutistepSendCommand extends Command
{
    protected $signature = 'sms:campaigns-mutistep-send';

    protected $description = 'Checks if a multistep campaign needs sending and sends it if so.';

    public function handle(): void
    {
        SmsCampaignSend::where(['status' => SmsCampaignStatusEnum::in_progress()->value])
            ->where('next_step_timestamp', '<=', now())
            ->get()
            ->each(fn(SmsCampaignSend $smsCampaignSend) => SendCampaignService::continueSend($smsCampaignSend));
    }
}
