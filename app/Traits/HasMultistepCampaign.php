<?php

namespace App\Traits;

trait HasMultistepCampaign
{
    const CAMPAIGN_SETTINGS_KEY_MULTISTEP = 'multistep';

    public function getMultistepSettings(): array
    {
        return $this->getSettings()[self::CAMPAIGN_SETTINGS_KEY_MULTISTEP] ?? [];
    }

    public function hasMultistep()
    {
        return $this->getSettings()[self::CAMPAIGN_SETTINGS_KEY_MULTISTEP] ?? false;
    }
}