<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmsCampaignSendResource\Pages;
use App\Models\SmsCampaignSend;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Livewire\Features\Placeholder;

class SmsCampaignSendResource extends Resource
{
    protected static ?string $model = SmsCampaignSend::class;

    protected static ?string $slug = 'sms-campaign-sends';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                Group::make()
//                    ->schema([
//                        Card::make()
//                            ->schema(static::getFormSchema('aa'))
//                            ->columns(2),
//
//                        Section::make('Order items')
//                            ->schema(static::getFormSchema('items')),
//                    ])
//                    ->columnSpan(['lg' => fn (?SmsCampaignSend $record) => $record === null ? 3 : 2]),

//                Card::make()
//                    ->schema([
//                        \Filament\Forms\Components\Placeholder::class::make('created_at')
//                            ->label('Created at')
//                            ->content(fn (SmsCampaignSend $record): ?string => $record->created_at?->diffForHumans()),
//
//                        \Filament\Forms\Components\Placeholder::make('updated_at')
//                            ->label('Last modified at')
//                            ->content(fn (SmsCampaignSend $record): ?string => $record->updated_at?->diffForHumans()),
//                    ])
//                    ->columnSpan(['lg' => 1])
//                    ->hidden(fn (?SmsCampaignSend $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign_id'),

                TextColumn::make('country_id'),

                TextColumn::make('send_vars'),

                TextColumn::make('status'),

                TextColumn::make('date_created')
                    ->date(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSmsCampaignSends::route('/'),
            'create' => Pages\CreateSmsCampaignSend::route('/create'),
            'edit' => Pages\EditSmsCampaignSend::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

//    private static function getFormSchema(string $string)
//    {
//        return [];
//    }
}
