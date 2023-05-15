<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmsCampaignSendResource\Pages;
use App\Models\SmsCampaignSend;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;

class SmsCampaignSendResource extends Resource
{
    protected static ?string $model = SmsCampaignSend::class;

    protected static ?string $slug = 'sms-campaign-sends';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('campaign_id'),

                TextInput::make('country_id'),

                TextInput::make('status')
                    ->integer(),

                DatePicker::make('date_created'),
            ]);
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
}
