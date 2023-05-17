<div>
    <div class="mb-4">
        <div class="filament-tables-header-heading text-l font-bold tracking-tight">
            Sender ID
        </div>
        {{--        <p class="opacity-70 text-sm">--}}
        {{--            Add a text message to your campaign.--}}
        {{--        </p>--}}
    </div>

    <input type="text"
           wire:model.debounce="text"
           @keydown.enter="$wire.save()"
           name="text" id="sender_id_input"
           maxlength="8" pattern="[a-zA-Z0-9]+"
            @class(
    [
    'filament-forms-textarea-component filament-forms-input block w-full transition duration-75 rounded-lg shadow-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70',
    'border-gray-300' => ! $errors->has('text'),
    'border-danger-600 ring-danger-600' => $errors->has('text'),
]
)>
    @error('text') <span
            class="filament-forms-field-wrapper-error-message text-sm text-danger-600">{{ $message }}</span> @enderror

    <div class="">
        <div class="text-right mt-3">
            <div class="filament-button filament-button-size-sm inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2rem] px-3 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                <a href="#" wire:click.prevent="save">
                    <span class="">Save</span>
                </a>
            </div>
        </div>
    </div>
</div>