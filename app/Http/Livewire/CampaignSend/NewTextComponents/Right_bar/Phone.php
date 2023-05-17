<?php

namespace App\Http\Livewire\CampaignSend\NewTextComponents\Right_bar;

use Livewire\Component;

class Phone extends Component
{
    public $text = 'SMS text';

    //https://github.com/acpmasquerade/sms-counter-php
    public $text_length = 0;
    public $text_remaining = 0;
    public $text_per_message = 160;

    //add listener to refresh when text is added
    protected $listeners = ['textUpdated' => 'textUpdated', 'senderidUpdated' => 'senderidUpdated'];
    public $senderid = 'Sender ID';

    public function render()
    {
        return view('livewire.campaign-send.new-text-components.right_bar.phone');
    }


    public function senderidUpdated($text)
    {
        if (empty($text)) {
            $this->senderid = 'Sender ID';
            return true;
        }
        $this->senderid = $text;
    }
    public function textUpdated($text)
    {
        if (empty($text['text'])) {
            $this->text = 'Text';
            $this->text_length = 0;
            $this->text_per_message = 160;
            $this->text_remaining = 0;
            return true;
        }
        $this->text = $text['text'];
        $this->text_length = $text['size']['length'];
        $this->text_per_message = $text['size']['per_message'];
        $this->text_remaining = $text['size']['remaining'];
    }
}
