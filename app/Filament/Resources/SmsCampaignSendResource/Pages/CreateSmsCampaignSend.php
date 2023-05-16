<?php

namespace App\Filament\Resources\SmsCampaignSendResource\Pages;

use App\Filament\Resources\SmsCampaignSendResource;
use App\Forms\Components\CreateSmsTextField;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use send\CreateCampaignSmsList;

class CreateSmsCampaignSend extends CreateRecord
{
    use HasWizard;

    protected static string $resource = SmsCampaignSendResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Audience')
                ->schema([
                    Card::make([
                        Select::make('list_id')
                            ->options(\Auth::user()->currentTeam->lists->pluck('name', 'id')->toArray())
                            ->label('Lists')
                            ->multiple()
                            ->required(),
                    ])->columns(),


                    Grid::make(3)
                        ->schema([
                            Card::make([
                                View::make('livewire.campaign-send.new-text-form')
                            ])->columnSpan(2),
                            Card::make([
                                View::make('livewire.campaign-send.new-text-components.right-sidebar'),
                            ])->columnSpan(1),
                        ]),
                ]),

            Step::make('Text')
                ->schema([
                    Card::make([

                    ]),
                ]),

            Step::make('Settings')
                ->schema([
                    Card::make([

                    ]),
                ]),
        ];
    }
}
