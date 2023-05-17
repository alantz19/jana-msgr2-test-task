<?php

namespace App\Http\Livewire\CampaignSend\NewSenderidForm;

use App\Models\SmsCampaignSenderid;
use App\Notifications\ModalNotification;
use Filament\Notifications\Notification;
use Livewire\Component;

class CreateSenderid extends Component
{
    public $text;
    public $campaign_id;
    protected $show_other = false;

    public function mount($campaign_id)
    {
        $this->campaign_id = $campaign_id;
    }

    public function render()
    {
        return view('livewire.campaign-send.new-senderid-form.create-senderid');
    }

    public function save()
    {
        $validated = $this->validate([
            'text' => 'required',
        ]);

        $model = SmsCampaignSenderid::make($validated);
        $model->campaign_id = $this->campaign_id;
        $model->save();

        $this->text = '';
        $this->emit('senderidUpdated', '');

        Notification::make()->success()->title('Senderid added')->send();
    }

    public function updatedText($value)
    {
//        $this->emit('senderidUpdated', $value);
    }
}
