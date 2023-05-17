<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use App\Http\Livewire\CampaignSend\NewTextForm\TextList;
use App\Models\SmsCampaign;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\ViewField;
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
    public $textCount = 0;

    protected static string $resource = CampaignResource::class;

    protected static string $view = 'filament.resources.campaign-resource.pages.create-campaign-send';

    protected static ?string $title = 'Create Campaign Send';

    protected $listeners = ['textAddedCount' => 'updateTextCount'];

    //add function that will update textCount attribute
    public function updateTextCount($count)
    {
        $this->textCount = $count;
    }

    //add textCount attribute


    public function mount($record)
    {
        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        $this->smsCampaign = $this->resolveRecord($record);
        $this->textCount = TextList::getTextListQuery($this->smsCampaign->id)->count();
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
                                    ViewField::make('textCount')->view('livewire.campaign-send.new-text-form')->label(false)->rules([
                                        function () {
                                            return function (string $attribute, $value, Closure $fail) {
                                                if ($value < 1) {
                                                    $fail("At least 1 SMS text is required");
                                                }
                                            };
                                        },
                                    ])
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
