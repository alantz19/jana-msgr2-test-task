<x-dynamic-component
        :component="$getFieldWrapperView()"
        :id="$getId()"
        :label="$getLabel()"
        :label-sr-only="$isLabelHidden()"
        :helper-text="$getHelperText()"
        :hint="$getHint()"
        :hint-action="$getHintAction()"
        :hint-color="$getHintColor()"
        :hint-icon="$getHintIcon()"
        :required="$isRequired()"
        :state-path="$getStatePath()"
>
<div>
    @livewire('campaign-send.new-text-form.create-text', ['campaign_id' => $this->smsCampaign->id])
    <div class="mt-9">
        @livewire('campaign-send.new-text-form.text-list', ['campaign_id' => $this->smsCampaign->id])
    </div>

</div>
</x-dynamic-component>