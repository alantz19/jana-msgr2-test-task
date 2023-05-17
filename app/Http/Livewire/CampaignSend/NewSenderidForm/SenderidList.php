<?php

namespace App\Http\Livewire\CampaignSend\NewSenderidForm;

use App\Models\SmsCampaignSenderId;
use App\Models\SmsCampaignText;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

class SenderidList extends Component implements HasTable
{
    use InteractsWithTable;

    //add listener to refresh when text is added
    protected $listeners = ['senderidUpdated' => '$refresh'];
    public $campaign_id;

    protected function getFormModel(): Model|string|null
    {
        return SmsCampaignSenderId::class;
    }

    public function dehydrate()
    {
        $this->emit('senderidCount', $this->table->getAllRecordsCount());
    }

    public function mount($campaign_id)
    {
        $this->campaign_id = $campaign_id;
        $this->table = $this->getTable();
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
        return 'No Sender IDs yet';
    }

    protected function getTableQuery(): Builder
    {
        return self::getSenderIdListQuery($this->campaign_id);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getSenderIdListQuery($campaign_id)
    {
        return SmsCampaignSenderId::query()
            ->where(['campaign_id' => $campaign_id ]);
    }

    public function render()
    {
        return view('livewire.campaign-send.new-senderid-form.senderid-list');
    }
}
