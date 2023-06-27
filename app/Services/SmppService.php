<?php

namespace App\Services;

use App\Http\Requests\SmsRouteSmppCreateRequest;
use App\Models\SmsRouteSmppConnection;
use common\models\Telecom\SMPP\SMPP;
use common\models\Telecom\SMPP\SmppClient;
use common\models\Telecom\Transport\SocketTransport;
use Exception;

class SmppService
{
    public static function testConnection(SmsRouteSmppConnection $data): bool
    {
        return true; //todo when implementing smpp
    }

    public static function sendSms(SmsRouteSmppConnection $data, string $phone, string $message)
    {

    }

    /**
     * @param $url
     * @param $port
     * @param $username
     * @param $password
     * @param bool $receiver or transmitter
     * @return SmppClient
     * @throws Exception
     */
    static public function getSmppClient($url, $port, $username, $password, $receiver = false, $gtw_type = null,
                                         $transceiver = false, $timeout = 10000)
    {
        $transport = new SocketTransport(array($url), $port, $persist = true);
        $transport->setRecvTimeout($timeout);
        $transport->setSendTimeout($timeout);

        SmppClient::$sms_null_terminate_octetstrings = false;
        SmppClient::$csms_method = SmppClient::CSMS_PAYLOAD;
        SmppClient::$sms_registered_delivery_flag = SMPP::REG_DELIVERY_SMSC_BOTH;
        $smpp = new SmppClient($transport);

        if (!empty($gtw_type)) {
            $smpp->gtw_type = $gtw_type;
        }
        if (SmppClient::$system_type != 'HLR') {
            if ('smpp.winengage.com' === $url ||
                (!empty($smpp->gtw_type) && $smpp->gtw_type == 'textlocal')) { // TODO: correct?
                SmppClient::$system_type = 'SMPP';
            } elseif (stripos($url, 'go4mobility.com') !== false ||
                (!empty($smpp->gtw_type) && $smpp->gtw_type == 'mobility')) {
                SmppClient::$system_type = '1572';
            } elseif (!empty($smpp->gtw_type) && $smpp->gtw_type == 'clickatell') {
                if ($username == 'smsedge_sub') {
                    SmppClient::$system_type = '3652261';
                } elseif ($username == 'smsedge') {
                    SmppClient::$system_type = '3649508';
                }
            } elseif (!empty($smpp->gtw_type) && $smpp->gtw_type == 'wavy') {
                SmppClient::$system_type = 'MovileSMSC';
            } elseif (!empty($smpp->gtw_type) && $smpp->gtw_type == 'businesslead') {
                SmppClient::$system_type = 'SMPP';
            } else {
                SmppClient::$system_type = '';
            }
        }

        $smpp->debug = true;
        $transport->debug = true;

        try {
            $transport->open();
            if ($transceiver) {
                $smpp->bindTransceiver($username, $password);
            } elseif ($receiver) {
                $smpp->bindReceiver($username, $password); //in the future if we would like to receive DLR's
            } else {
                $smpp->bindTransmitter($username, $password);
            }
        } catch (Exception $e) {
            $msg = "{$e->getMessage()}\n - {$username}@{$url}:{$port}";
            throw new Exception($msg, $e->getCode());
        }

        return $smpp;
    }
}
