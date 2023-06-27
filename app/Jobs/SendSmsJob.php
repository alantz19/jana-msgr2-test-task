<?php

namespace App\Jobs;

use App\Data\BuildSmsToSendSmsData;
use App\Models\SmsRoute;
use App\Models\SmsRouteSmppConnection;
use App\Services\SendingProcess\Routing\SmsRoutingSmppClientService;
use App\Services\SendingProcess\Telecom\SMPP\SmppClient;
use App\Services\SmppService;
use DB;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public BuildSmsToSendSmsData $dto)
    {
    }

    public function handle(): void
    {
        /**
         *             'sms_id' => $data->sms_id,
         * 'phone_normalized' => $data->sendToBuildSmsData->phone_normalized,
         * 'contact_id' => $data->sendToBuildSmsData->contact_id,
         * 'final_text' => $data->finalText,
         * 'final_text_msg_parts' => $data->final_text_msg_parts,
         * 'selected_route_id' => $data->selectedRoute->selected_route_id,
         * 'sms_campaign_id' => $data->sendToBuildSmsData->sms_campaign_id,
         * 'sms_campaign_send_id' => $data->sendToBuildSmsData->sms_campaign_id,
         */
        Log::debug("Submitting sms to sms provider", ['sms_id' => $this->dto->buildSmsData->sms_id]);

        //submit to sms provider
        $route = SmsRoute::find($this->dto->buildSmsData->selectedRoute->selected_route_id);
        self::testDbConnection();
        if ($route->connection_type === SmsRouteSmppConnection::class) {
            $smpp = SmsRouteSmppConnection::find($route->connection_id);
            try {
                $client = SmsRoutingSmppClientService::createSmppClient($smpp);
            } catch (Exception $e) {
                Log::error("Error creating smpp client",
                    ['sms_id' => $this->dto->buildSmsData->sms_id, 'error' => $e->getMessage()]);
                $this->fail();
                return;
            }

            $res = $client->sendSms($this->dto->buildSmsData->selected_senderderid_text,
                $this->dto->buildSmsData->sendToBuildSmsData->phone_normalized,
                $this->dto->buildSmsData->finalText,
                $this->dto->buildSmsData->final_text_is_unicode,
                $this->dto->buildSmsData->final_text_msg_parts > 1,
            );
            if (!$res) {
                $this->fail('Failed to send SMS');
                return;
            }
            $msgId = isset($results['msgid']) ? $results['msgid'] : '';

            $client->syncSmppDlrs();

        } else {
            Log::warning('Other connection types not implemented yet');
        }
        //save results to send log
        $this->saveResultsToSmsSendlog($res);
    }

    private static function testDbConnection()
    {
        try {
            DB::connection('pgsql')->select('select 1=1;');
        } catch (Exception $e) {
            DB::connection('pgsql')->reconnect();
        }
        try {
            DB::connection('clickhouse')->select('select 1=1;');
        } catch (Exception $e) {
            DB::connection('clickhouse')->reconnect();
        }
    }

    private function saveResultsToSmsSendlog()
    {

    }
}
