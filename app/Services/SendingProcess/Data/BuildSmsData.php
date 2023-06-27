<?php

namespace App\Services\SendingProcess\Data;

use App\Data\CampaignSendToBuildSmsData;
use App\Data\SmsRoutingPlanSelectedData;
use App\Models\Domain;
use App\Models\Offer;
use App\Models\SmsCampaignText;

class BuildSmsData
{
    public SmsCampaignText $selectedCampaignText;
    public $sms_shortlink;
    public $segment_id; //todo
    public CampaignSendToBuildSmsData $sendToBuildSmsData;
    /**
     * @var Offer|mixed
     */
    public Offer $selectedOffer;
    public Domain $domain;
    public $submited_text_parts;
    /**
     * @var true
     */
    public bool $is_initial_msg_long;
    public string $sms_id;
    public string $sms_optout_link;
    /**
     * @var array|string|string[]|null
     */
    public string|array|null $finalText;
    public int $final_text_msg_parts;
    public $final_text_is_unicode = false;

    public array $keyword_data = [];
    public string $scheme;
    public SmsRoutingPlanSelectedData $selectedRoute;
    /**
     * @var mixed|string
     */
    public string $selected_senderderid_id;
    public $selected_senderderid_text;

    public function getReplacementParams()
    {
        //todo
        return [];
        $meta = $this->numberObj->getMeta();
        $params = [
            'ad_id' => $this->selectedAdText->ad_id,
            'domain_tag' => $this->domainTag,
            'phone' => $this->numberObj->normalized,
            'camp_id' => $this->lapObj->campaign_id,
            'tag' => $this->lapObj->getCampaign()->tag,
            'cost' => 0, //todo
            'route' => $this->lapObj->getGateway()->name,
        ];

        $metaParams = [];
        if (!empty($meta)) {
            $metaParams = [
                'name' => isset($meta['name']) ? ucfirst(strtolower(explode(' ', $meta['name'])[0])) : null,
                'fname' => isset($meta['name']) ? ucfirst(strtolower(explode(' ', $meta['name'])[0])) : null,
                'lname' => $meta['lname'] ?? null,
                'email' => $meta['email'] ?? null,
            ];
        }

        $customParams = [];
        if (!empty($this->numberObj->numbersData)) {
            $vars = [
                'custom1',
                'custom2',
                'custom3',
                'custom4',
                'custom5',
            ];
            foreach ($vars as $var) {
                $customParams[$var] = trim($this->numberObj->numbersData->{$var});
            }//end foreach
        }

        $state = $this->numberObj->state;
        $stateParams = [];
        if ($state) {
            $stateParams = [
                'state' => $state->name,
                'state_code' => $state->code,
            ];
        }

        $this->_cache['replacement_params'] = array_merge($customParams, $metaParams, $stateParams, $params);
        return $this->_cache['replacement_params'];
    }

    public function getShortLink()
    {
        return $this->scheme . '://' . $this->keyword_data['url'];
    }

    public function getOptOutLink()
    {
        return $this->scheme . '://' . $this->keyword_data['opt-out'];
    }
}
