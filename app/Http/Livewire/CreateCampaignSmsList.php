<?php

namespace App\Http\Livewire;

use App\Models\SmsCampaign;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

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
        return [];
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

    protected function getTableQuery(): Builder
    {
        return SmsCampaign::query();
    }
}
