<?php

namespace App\Http\Livewire\CampaignSend\NewTextComponents\Right_bar;

use Livewire\Component;

class Phone extends Component
{
    public $text = 'test';

    //add listener to refresh when text is added
    protected $listeners = ['textUpdated' => 'textUpdated'];

    public function render()
    {
        return view('livewire.campaign-send.new-text-components.right_bar.phone');
    }

    public function textUpdated($text)
    {
        $this->text = $text;
    }
}
