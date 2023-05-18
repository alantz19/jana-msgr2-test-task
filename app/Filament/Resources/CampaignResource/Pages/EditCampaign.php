<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\View\View;

class EditCampaign extends EditRecord
{
    protected static string $resource = CampaignResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('Send')
            ->url(route('filament.resources.campaigns.send', $this->record->id))
        ];
    }
}
