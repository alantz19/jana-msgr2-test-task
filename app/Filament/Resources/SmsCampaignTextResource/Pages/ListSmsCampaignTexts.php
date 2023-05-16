<?php

namespace App\Filament\Resources\SmsCampaignTextResource\Pages;

use App\Filament\Resources\SmsCampaignTextResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSmsCampaignTexts extends ListRecords
{
    protected static string $resource = SmsCampaignTextResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
