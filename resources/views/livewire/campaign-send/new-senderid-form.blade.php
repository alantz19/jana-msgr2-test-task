<div>
    @livewire('campaign-send.new-senderid-form.create-senderid', ['campaign_id' => $this->smsCampaign->id])
    <div class="mt-9">
        @livewire('campaign-send.new-senderid-form.senderid-list', ['campaign_id' => $this->smsCampaign->id])
    </div>
</div>