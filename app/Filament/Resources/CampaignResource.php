<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Filament\Resources\SmsCampaignResource\RelationManagers\SmsCampaignSendsRelationManager;
use App\Models\SmsCampaign;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CampaignResource extends Resource
{
    protected static ?string $model = SmsCampaign::class;

    protected static ?string $slug = 'campaigns';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                Select::make('team_id')
//                    ->relationship('team', 'name')
//                    ->searchable(),

//                TextInput::make('name'),

//                Placeholder::make('created_at')
//                    ->label('Created Date')
//                    ->content(fn(?SmsCampaign $record): string => $record?->created_at?->diffForHumans() ?? '-'),

//                Placeholder::make('updated_at')
//                    ->label('Last Modified Date')
//                    ->content(fn(?SmsCampaign $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
//                TextColumn::make('team.name')
//                    ->searchable()
//                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaigns::route('/'),
            'send' => Pages\CreateCampaignSend::route('/{record}/send'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['team']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'team.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->team) {
            $details['Team'] = $record->team->name;
        }

        return $details;
    }

//    public static function getRelations(): array
//    {
//        return [
//            'sends' => SmsCampaignSendsRelationManager::class
//        ]
//    }
}
