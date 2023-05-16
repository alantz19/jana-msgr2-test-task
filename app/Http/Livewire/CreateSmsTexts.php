<?php

namespace App\Http\Livewire;

use App\Models\SmsCampaignText;
use Livewire\Component;

class CreateSmsTexts extends Component
{
    public $text;
    protected $campaign_id;

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
    }
    public function render()
    {
        return view('livewire.create-sms-texts');
    }


}
