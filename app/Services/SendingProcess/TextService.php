<?php

namespace App\Services\SendingProcess;

use api\v2\sms\sends\campaigns\sending_services\models\CampaignSmsMessage;
use api\v2\sms\sends\campaigns\sending_services\UrlShortenerService;
use App\Dto\buildSmsDto;
use App\Models\SmsCampaignText;
use App\Services\SendingProcess\Data\BuildSmsData;
use backend\models\AdTexts;
use backend\models\Translations;
use common\components\SMSCounter;
use common\models\Telecom\Encoder\GsmEncoder;

class TextService
{
    public static function maxTextParts(int $adId)
    {
        return AdTexts::find()
            ->where(['ad_id' => $adId])
            ->select('max(parts)')
            ->scalar();
    }

    private static function metaTextReplacements(mixed $text, CampaignSmsMessage $msg)
    {
        $params = $msg->getReplacementParams();
        foreach ($params as $shortcode => $val) {
            $textBefore = $text;
            $text = self::metaTextReplacement($text, $shortcode, $val);
            if (self::getParts($text) > self::getParts($textBefore)) {
                $text = self::metaTextReplacement($textBefore, $shortcode, '');
            }
        }

        return $text;
    }

    private static function replaceRandomDigits($text)
    {
        // replaces {rand} with random 3 letter string, max 8 times
        for ($i = 0; str_contains($text, '{rand}') && $i < 8; $i++) {
            $text = preg_replace('/{rand}/', Yii::$app->security->generateRandomString(3), $text, 1);
        }

        for ($i = 0; str_contains($text, '{d}') && $i < 32; $i++) {
            $text = preg_replace('/{d}/', rand(0, 9), $text, 1);
        }

        return $text;
    }

    private static function cleanTextFromShortcodes($text)
    {
        $text = preg_replace('/{{{.*?}}}/', '', $text);
        return preg_replace('/{.*?}/', '', $text);
    }

    private static function metaTextReplacement($text, $shortcode, $metaVal)
    {
        if (str_contains($text, "\{$shortcode\}" && !empty($metaVal))
        ) {
            $originalText = $text;
            $originalParts = self::getParts($text);
//            $metaVal = $meta ?? ucfirst(strtolower(explode(' ', $meta[$shortcode])));
            $text = str_replace("\{$shortcode\}", $metaVal, $text);

            if (self::getParts($text) > $originalParts) {
                // string in name might be utf-8 encoded
                $text = str_replace("\{$shortcode\}", mb_substr($metaVal, 0, 6, 'UTF-8'), $originalText);
                if (self::getParts($text) > $originalParts) {
                    $text = str_replace("\{$shortcode\}", '', $originalText);
                }
            }
        }

        return $text;
    }

    public static function processMsg(buildSmsDto $msg, BuildSmsData $data)
    {
        $data->selectedCampaignText = self::getSpecificAdText($msg->campaign_id, $msg->counter);

        self::processTextReplacement($data);
    }

    public static function processTextReplacement(BuildSmsData $msg)
    {
        //todo:continue..
        $text = $msg->selectedAdText->text;
        \Yii::$app->logger->logDebug('text before replacement', ['text' => $text]);
        if ($msg->selectedAdText->haveDomainOrOptoutTag()) {
            UrlShortenerService::setShortlink($msg);
            $msg->sms_optout_link = UrlShortenerService::getDynamicSmsOptOut($msg->sms_shortlink);
        }

        self::setSmsLength($text, $msg);


        $text = self::mandatoryTextReplacements($text);
        \Yii::$app->logger->logDebug('after mandatory', ['text' => $text]);
        $text = self::optionalTextReplacements($text);
        \Yii::$app->logger->logDebug('after optional', ['text' => $text]);
        $text = self::metaTextReplacements($text, $msg);
        \Yii::$app->logger->logDebug('after meta', ['text' => $text]);
        $text = self::replaceRandomDigits($text);
        \Yii::$app->logger->logDebug('after rand-digits', ['text' => $text]);
        $text = self::processSpintext($text);
        \Yii::$app->logger->logDebug('after spintext', ['text' => $text]);
        $text = self::cleanTextFromShortcodes($text);
        \Yii::$app->logger->logDebug('after cleantextfromshortcodes', ['text' => $text]);

        //todo - refactor out number replacement and clean text..
        $text = Translations::replaceMessage($msg->lapObj->getCampaign()->user->translationMessage(), $text);
        $msg->finalText = $text;
        \Yii::$app->logger->logDebug('Final text', ['text' => $text]);

        return $text;
    }//end setFinalText()

    private static function getSpecificAdText($campaign_id, $counter)
    {
        $adTexts = SmsCampaignText::where(['campaign_id' => $campaign_id, 'active' => 1])->all();
        return $adTexts[($counter % count($adTexts))];
    }//end selectSpecificAdText()

    public static function optionalTextReplacements(string $text)
    {
        $originalText = $text;
        $route_name = self::$_msg->lapObj->getGateway()->name;
        $shortcodes = [
            '{{{code}}}' => self::$_msg->random_key,
            '{{{phone}}}' => self::$_msg->normalized,
            '{{{ad_id}}}' => self::$_msg->selectedAdText->id,
            '{{{dayofweek}}}' => date('l'),
            '{{{ROUTE}}}' => $route_name,
            '{{{route}}}' => $route_name,
            '{ROUTE}' => $route_name,
            '{route}' => $route_name,
        ];
        foreach ($shortcodes as $shortcode => $replacement) {
            $text = str_replace($shortcode, $replacement, $text);
        }

        if (self::getParts($text) > self::getParts($originalText)) {//if bigger clean text from previous shortcodes..
            $text = $originalText;
            foreach ($shortcodes as $shortcode => $replacement) {
                $text = str_replace($text, $shortcode, ' ');
            }
        }
        return $text;
    }//end optionalTextReplacements()

    public static function mandatoryTextReplacements($text)
    {
        $shortlink = self::$_msg->sms_shortlink;
        $optout = self::$_msg->sms_optout_link;

        if (str_contains($text, '{{{domain}}}')) {
            $text = str_replace('{{{domain}}}', $shortlink, $text);
        }

        if (str_contains($text, '{{{optout}}}')) {
            $text = str_replace('{{{optout}}}', $optout, $text);
        }

        $text = str_replace("\n", ' ', $text);
        // removed - if (!$spaces_allowed) {
        $text = preg_replace('~\s+~', ' ', $text);

        if (self::getParts($text) > self::$_msg->submited_text_parts) {
            Yii::$app->logger->logWarning(
                'Initial msg was short after replacing was long - mandatory',
                ['original' => self::$_msg->selectedAdText->text, 'replaced' => $text]
            );
        }

        $text = self::findBadSymbols($text);
        $text = htmlspecialchars_decode($text);

        $trim_gsm = intval(self::$_msg->lapObj->getGateway()->getType()->one()->trim_gsm);
        if (!self::$_msg->selectedAdText->isUnicode() && $trim_gsm) {
            $text = GsmEncoder::utf8_to_gsm0338_transliterate($text);
            Yii::$app->logger->logDebug("Trimmed Message: $text");
        }

        return $text;
    }//end mandatoryTextReplacements()

    public static function getParts(string $text)
    {
        $msgs = (new SMSCounter())->count($text);

        return $msgs->messages;
    }//end getParts()
    private static function processSpintext(mixed $text)
    {
        //        preg_match_all('/\{{{[\s\S]*\|[\s\S\|]*\}}}/isU', $text, $m);
        preg_match_all('/\{{3}[^\{]*\|.*\}{3}/isU', $text, $m);

        if (isset($m[0])) {
            foreach ($m[0] as $spin) {
                $tmp = str_replace('{{{', '', $spin);
                $tmp = str_replace('}}}', '', $tmp);
                $tmp_arr = explode('|', $tmp);
                $tmp_arr = array_filter($tmp_arr, function ($v) {
                    $v = trim($v);
                    if (empty($v)) {
                        return false;
                    }
                    return true;
                });
                if (!empty($tmp_arr)) {
                    $k = array_rand($tmp_arr, 1);
                    $replace = trim($tmp_arr[$k]);
                } else {
                    $replace = '';
                }
                $text = str_replace($spin, $replace, $text);
            }
        }
        return $text;
    }

    private static function setSmsLength(mixed $text, CampaignSmsMessage $msg)
    {
        $parts = self::getParts($text);
        $msg->submited_text_parts = $parts;
        if ($parts > 1) {
            \Yii::$app->logger->logDebug('message too long');
            $msg->is_initial_msg_long = true;
        }
    }

    private static function findBadSymbols(string $msg)
    {
        $message = preg_replace(
            [
                '/\x{008C}/u',
                '/\x{0080}/u',
                '/\x{0081}/u',
                '/\x{0082}/u',
                '/\x{0083}/u',
                '/\x{0084}/u',
                '/\x{0085}/u',
                '/\x{0086}/u',
                '/\x{0087}/u',
                '/\x{0088}/u',
                '/\x{0089}/u',
                '/\x{008B}/u',
                '/\x{008D}/u',
                '/\x{008E}/u',
                '/\x{008F}/u',
                '/\x{0090}/u',
                '/\x{0091}/u',
                '/\x{0092}/u',
                '/\x{0093}/u',
                '/\x{0094}/u',
                '/\x{0095}/u',
                '/\x{0096}/u',
                '/\x{0097}/u',
                '/\x{0098}/u',
                '/\x{0099}/u',
                '/\x{009A}/u',
                '/\x{009B}/u',
                '/\x{009C}/u',
                '/\x{009D}/u',
                '/\x{009E}/u',
                '/\x{009F}/u',
            ],
            '',
            $msg
        );

        // users very often put this ...
        $message = preg_replace('/\x{2019}/u', "'", $message);
        // right single quotation mark
        $message = preg_replace('/\x{00A0}/u', ' ', $message);
        // no-break space
        return $message;
    }//end findBadSymbols()
}
