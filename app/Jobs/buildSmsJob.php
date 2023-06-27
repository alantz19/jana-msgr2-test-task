<?php

namespace App\Jobs;

use App\Data\CampaignSendToBuildSmsData;
use App\Models\SmsCampaignSend;
use App\Models\SmsRoutingPlan;
use App\Services\SendingProcess\Data\BuildSmsData;
use App\Services\SendingProcess\Routing\SmsRoutingPlanSelectorService;
use App\Services\SendingProcess\TextService;
use App\Services\SmsRoutingPlanService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Log;
use Str;

class buildSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public CampaignSendToBuildSmsData $dto)
    {
    }

    public function handle(): void
    {
        //check stop conditions
        if (SmsCampaignSend::find($this->dto->sms_campaign_send_id)->status == 'stopped') {
            return;
        }

        $res = Cache::pull($this->dto->phone_normalized . '_' . $this->dto->team_id);
        if ($res) {
            Log::warning('Duplicate sms cache: ' . $this->dto->phone_normalized . '_' . $this->dto->team_id);
            return;
        }

        Cache::put($this->dto->phone_normalized . '_' . $this->dto->team_id, true, 20);

        $data = new BuildSmsData();
        $data->sendToBuildSmsData = $this->dto;
        $data->sms_id = Str::uuid()->toString();
        TextService::processMsg($data);

        //decide on route.
        if ($this->dto->sms_routing_plan_id) {
            $plan = SmsRoutingPlan::where(['id' => $this->dto->sms_routing_plan_id, 'team_id' => $this->dto->team_id])
                ->first();
        } else {
            $plan = SmsRoutingPlanService::getDefaultRoutingPlan($this->dto->team_id);
        }
        if (!$plan) {
            throw new Exception('No routing plan found for team: ' . $this->dto->team_id);
        }
        $data->sms_routing_plan_id = $plan->id;
        SmsRoutingPlanSelectorService::createSelectorForBuildSms($plan, $data);


        //deduct balance

        //submit to sms build queue
    }
}
