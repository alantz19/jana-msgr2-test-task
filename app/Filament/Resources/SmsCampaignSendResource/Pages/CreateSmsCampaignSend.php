<?php

namespace App\Filament\Resources\SmsCampaignSendResource\Pages;

use App\Filament\Resources\SmsCampaignSendResource;
use App\Forms\Components\CreateSmsTextField;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateSmsCampaignSend extends CreateRecord
{
    use HasWizard;

    protected static string $resource = SmsCampaignSendResource::class;

    public $sms_text;

    protected function getSteps(): array
    {
        return [
            Step::make('Audience')
                ->schema([
                    Card::make([
                        CreateSmsTextField::make('sms_text')
                            ->required()
                            ->campaignId(1)
                            ->reactive()
                            ->label('SMS Text'),
//                            ->applyStateBindingModifiers('sms_text')
                        Select::make('list_id')
                            ->options(\Auth::user()->currentTeam->lists->pluck('name', 'id')->toArray())
                            ->label('Lists')
                            ->multiple()
                            ->required(),

                    ])->columns(),
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
