<?php

namespace App\Services;

use api\v2\sms\sends\campaigns\sending_services\DomainsService;
use api\v2\sms\sends\campaigns\sending_services\models\CampaignSmsMessage;
use App\Services\SendingProcess\Data\BuildSmsData;
use common\components\Shorty;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UrlShortenerService
{
    private static function callUrlShortener(BuildSmsData $msg, $shortyParams = [])
    {
        $i = 0;
        $res = null;
        do {
//            return "local.dev/abc";
            //no need to create model.. i think can be called from here.

            $res = self::createKeyword(
                $msg->selectedOffer->url,
                $msg->domain->domain,
                $msg->dto->sms_campaign_id,
                [
                    'sms_id' => $msg->sms_id,
                    ...$shortyParams,
                ],
            );

            if ($res) {
                break;
            }

//            echo '.';
            usleep(rand(200000, 700000));

            if ($i == 50) {
                throw new Exception("Failed to short link");
            }

            $i++;
        } while (!$res);

        $msg->keyword_data = $res['data'];
        $msg->scheme = 'http';

        if (!isset($shortyParams['is_http'])) {
            if ($msg->domain->https_support) {
                $msg->scheme = 'https';
            }
        }

        return $msg->getShortLink();
    }

    /**
     * @param  $domains
     * @throws Exception
     */
    public static function setShortlink(BuildSmsData $msg)
    {
        if (env('dont_send', false)) {
            throw new Exception('dont_send');
        }

        $offer = self::selectOffer($msg);
        $msg->selectedOffer = $offer;

        $urlShortenerParams = self::buildUrlShortenerPostParams($msg);
        $listOrSegmentId = $msg->dto->list_id;
        if (empty($listOrSegmentId) && !empty($msg->segment_id)) {
            $listOrSegmentId = 'segment_' . $msg->segment_id;
        }

//        DomainsService::setDomainTag($msg);
        $domain = DomainService::getDomainForCampaign($msg);
        if (!$domain) {
            throw new Exception('Failed to get shorty domain');
        }

        $msg->domain = $domain;
        Log::debug('Shorty domain', ['domain_id' => $domain->id]);

        $link = self::callUrlShortener($msg, $urlShortenerParams);
        if (empty($link)) {
            throw new Exception('Failed to create shortlink');
        }

        Log::debug("Received shortlink: $link");
        $msg->sms_shortlink = $link;
        $msg->sms_optout_link = $msg->getOptOutLink();

        return true;
    }//end getShortlink()

    private static function buildUrlShortenerPostParams(BuildSmsData $msg)
    {
        $neededParams = $msg->selectedOffer->getNeededParams();
        if (!$neededParams) {
            return [];
        }
        $replacementParams = $msg->getReplacementParams();
        foreach ($neededParams as $neededParam) {
            if (isset($replacementParams[$neededParam])) {
                $postParams[$neededParam] = $replacementParams[$neededParam];
            }
        }

        Log::debug('Shorty request params:', ['params' => $postParams]);
        return $postParams;
    }//end buildShortyPostParams()

    public static function getDynamicSmsOptOut($link)
    {
        return self::getDynamic($link, 'o');
    }

    private static function getDynamic($link, $prefix)
    {
        $parsed = parse_url($link);
        $dynamic = '';

        if (!empty($parsed['scheme']) && !empty($parsed['host']) && !empty($parsed['path'])) {
            $keyword = ltrim($parsed['path'], '/');
            $dynamic = $parsed['scheme'] . '://' . $parsed['host'] . '/' . trim($prefix, '/') . '/' . $keyword;
        }

        return $dynamic;
    }

    private static function selectOffer(BuildSmsData $msg)
    {
        $offers = $msg->dto->getCampaign()->offers()->where('is_active', true)->get();
        if (empty($offers)) {
            throw new \Exception('No offers found for campaign');
        }

        return $offers[$msg->dto->counter % count($offers)];
    }

    private static function createKeyword($link, $domain, $campaignId, $meta)
    {
        $data = [
            'link' => $link,
            'domain' => $domain,
            'campaign_id' => $campaignId,
            'meta' => $meta,
        ];

        $response = Http::withBody(json_encode($data))
            ->post(rtrim(config('services.shortener.url'), '/') . '/api/short-url');

        if ($response->ok()) {
            return $response->json();
        }

        Log::error('Failed to create keyword', [
            'request_data' => $data,
            'response' => $response->json(),
        ]);

        return null;
    }
}
