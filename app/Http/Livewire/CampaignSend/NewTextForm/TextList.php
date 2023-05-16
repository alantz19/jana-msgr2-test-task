<?php

namespace App\Http\Livewire\CampaignSend\NewTextForm;

use App\Models\SmsCampaignText;
//use Filament\Forms\Components\Component;
use Filament\Pages\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class TextList extends Component  implements HasTable
{
    use InteractsWithTable;

    //add listener to refresh when text is added
    protected $listeners = ['textAdded' => '$refresh'];

    protected function getFormModel(): Model|string|null
    {
        return SmsCampaignText::class;
    }

    public function mount()
    {
//        $this->table = $this->getTable();
    }

    public static function make($name): static
    {
        return new static($name);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('text')
        ];
    }

    protected function getTableActions(): array
    {
        return [
            DeleteAction::make()
                ->requiresConfirmation(false)
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No texts yet';
    }

    protected function getTableQuery(): Builder
    {
        return SmsCampaignText::query();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.campaign-send.new-text-form.text-list');
    }
}
