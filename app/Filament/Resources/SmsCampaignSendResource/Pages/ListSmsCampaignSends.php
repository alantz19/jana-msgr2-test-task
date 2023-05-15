<?php

namespace App\Filament\Resources\SmsCampaignSendResource\Pages;

use App\Filament\Resources\SmsCampaignSendResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSmsCampaignSends extends ListRecords
{
    protected static string $resource = SmsCampaignSendResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
