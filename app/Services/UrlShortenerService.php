<?php

namespace App\Services;

use api\v2\sms\sends\campaigns\sending_services\DomainsService;
use api\v2\sms\sends\campaigns\sending_services\models\CampaignSmsMessage;
use App\Services\SendingProcess\Data\BuildSmsData;
use common\components\Shorty;
use Exception;
use Illuminate\Support\Facades\Log;

class UrlShortenerService
{
    private static function callShorty(BuildSmsData $msg, $shortyParams = [])
    {
        $i = 0;
        do {
            return "local.dev/abc";
            //todo:call url shortener endpoint with domain data and params
            //no need to create model.. i think can be called from here.

            $shorty = new Shorty();
            $kw = $shorty->createKeyword(
                $msg->lapObj->getGroup()->foreign_id,
                $msg->random_key,
                $msg->lapObj->list_id,
                $shortyParams,
                $msg->domain->domain
            );
            if ($kw !== false) {
                break;
            }
            echo '.';
            usleep(rand(200000, 700000));

            if ($i == 50) {
                throw new Exception("Failed to short link");
            }
            $i++;
        } while ($kw === false);

        if (!isset($shortyParams['is_http'])) {
            if ($msg->domain->https_support) {
                return "https://{$msg->domain->domain}/$kw";
            }
        }

        return "http://{$msg->domain->domain}/$kw";
    }

    /**
     * @param  $domains
     * @throws Exception
     */
    public static function setShortlink(BuildSmsData $msg)
    {
        if (env('dont_send', false)) {
            return 'http://local.dev/abc';
        }

        if (!empty($msg->sms_shortlink)) {
            Log::warning('already called');
            return true;
        }

        $offer = self::selectOffer($msg);
        $msg->selectedOffer = $offer;

        $shortyPostParams = self::buildShortyPostParams($msg);
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

        $link = self::callShorty($msg, $shortyPostParams);
        if (empty($link)) {
            throw new Exception('Failed to create shortlink');
        }

        Log::debug("Received shortlink: $link");
        $msg->sms_shortlink = $link;

        return true;
    }//end getShortlink()

    private static function buildShortyPostParams(BuildSmsData $msg)
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
}
