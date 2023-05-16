<?php

namespace App\Filament\Resources\SmsCampaignTextResource\Pages;

use App\Filament\Resources\SmsCampaignTextResource;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSmsCampaignText extends EditRecord
{
    protected static string $resource = SmsCampaignTextResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
