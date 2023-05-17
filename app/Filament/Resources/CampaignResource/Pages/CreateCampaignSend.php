<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use App\Filament\Resources\SmsCampaignSendResource;
use App\Models\SmsCampaign;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;

class CreateCampaignSend extends Page implements HasForms
{
    use HasWizard;
    use InteractsWithForms;
    use InteractsWithRecord;

    public SmsCampaign $smsCampaign;

    public $lists = [];

    protected static string $resource = CampaignResource::class;

    protected static string $view = 'filament.resources.campaign-resource.pages.create-campaign-send';

    protected static ?string $title = 'Create Campaign Send';

    public function mount($record)
    {
        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        $this->smsCampaign = $this->resolveRecord($record);
    }

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Step::make('Audience')
                    ->schema([
                        Card::make([
                            Select::make('lists')
                                ->options(\Auth::user()->currentTeam->lists->pluck('name', 'id')->toArray())
                                ->label('Lists')
                                ->multiple()
                                ->required(),
                        ])->columns(),


                        Grid::make(3)
                            ->schema([
                                Card::make([
                                    View::make('livewire.campaign-send.new-text-form' , [ 'smsCampaign' => $this->smsCampaign ])
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
            ])
        ];
    }
}
