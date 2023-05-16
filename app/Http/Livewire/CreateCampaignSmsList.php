<?php

namespace App\Http\Livewire;

use App\Models\SmsCampaign;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Symfony\Component\DomCrawler\Field\TextareaFormField;

class CreateCampaignSmsList extends Component  implements HasTable
{
    use InteractsWithTable;

    public array $list;

    public function mount(array $list)
    {
        $this->list = $list;
    }
    
    public function render()
    {
        return view('livewire.create-campaign-sms-list');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('text')
        ];
    }

    protected function getTableFilters(): array
    {
        return [];
    }

    protected function getTableActions(): array
    {
        return [];
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
        return SmsCampaign::query();
    }
}
