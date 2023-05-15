<?php

namespace App\Filament\Resources\SmsCampaignSendResource\Pages;

use App\Filament\Resources\SmsCampaignSendResource;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSmsCampaignSend extends EditRecord
{
    protected static string $resource = SmsCampaignSendResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
