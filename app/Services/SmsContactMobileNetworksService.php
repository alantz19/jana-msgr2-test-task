<?php

namespace App\Services;

use App\Models\WorldMobileNetwork;

class SmsContactMobileNetworksService
{
    public static function getNetworks($contacts)
    {
        $contacts->each(function ($contact) {
            $contact->network = self::getNetwork($contact->phone_normalized);
        });
    }

    private static function getNetwork(int $phone_normalized)
    {
        ClickhouseService::getClient()->select('
            SELECT
                network
            FROM
                phone_networks
            WHERE
                phone = :phone
            LIMIT 1)',
            ['phone' => $phone_normalized]);
    }

    public static function saveNumberNetwork(int $phone_normalized, int $network_id): void
    {
        $network = WorldMobileNetwork::find($network_id);
        \DB::insert("INSERT INTO v2_numbers_networks (normalized, network_id, network_brand) VALUES (?, ?, ?)",
            [
                $phone_normalized,
                $network_id,
                $network->brand
            ]);
    }

    public static function getNetworkCacheForNumber($phone_normalized)
    {
        $res = ClickhouseService::getClient()->select('
        select 
            network_id
            from 
                v2_numbers_networks
            where
                normalized = :phone
        ',
            ['phone' => $phone_normalized])->rows();
        if (empty($res)) {
            return false;
        }

        return $res[0]['network_id'];
    }
}
