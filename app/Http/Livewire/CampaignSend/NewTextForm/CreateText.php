<?php

namespace App\Http\Livewire\CampaignSend\NewTextForm;

use App\Models\SmsCampaignText;
use Filament\Notifications\Notification;
use Livewire\Component;

class CreateText extends Component
{
    public $text;
    protected $campaign_id;

    protected $show_other = false;

    public function mount($campaign_id)
    {
        $this->campaign_id = $campaign_id;
    }

    public function save()
    {
        $validated = $this->validate([
            'text' => 'required|min:3',
        ]);

        SmsCampaignText::create([...$validated, 'campaign_id' => $this->campaign_id]);
        $this->text = '';
        $this->emit('textAdded');
        $this->emit('textUpdated', '');
        Notification::make()
            ->title('Text added')
            ->success()
            ->send();
    }
    public function render()
    {
        return view('livewire.campaign-send.new-text-form.create-text');
    }

    public function addText($text)
    {
        $this->text = $this->text . $text;
    }

    public function updatedText($value)
    {
        $this->emit('textUpdated', $value);
    }

}
