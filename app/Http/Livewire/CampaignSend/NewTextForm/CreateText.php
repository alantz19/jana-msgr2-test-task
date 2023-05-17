<?php

namespace App\Http\Livewire\CampaignSend\NewTextForm;

use App\Models\SmsCampaign;
use App\Models\SmsCampaignText;
use App\Notifications\ModalNotification;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
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

        $model = SmsCampaignText::make([...$validated]);
        $model->campaign_id = $this->campaign_id;
        $model->save();

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
        if (str_contains($text, '{domain}') && str_contains($this->text, '{domain}')) {
            ModalNotification::make()->title('Only one domain tag can be in text')->send();
            return true;
        }
        $this->text = trim($this->text . $text);
        $this->updatedText($this->text);
    }

    public function updatedText($value)
    {
        $size = \SMSCounter::count($value);
        $this->text = preg_replace('/\s+/', ' ', $value);

        $this->emit('textUpdated', ['text' => $value, 'size' => $size]);
    }

}
