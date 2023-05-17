<div>
    <div class="mb-4">
        <div class="filament-tables-header-heading text-l font-bold tracking-tight">
            Text
        </div>
        <p class="opacity-70 text-sm">
            Add a text message to your campaign.
        </p>
    </div>

    <textarea wire:model.debounce="text" @keydown.enter="$wire.save()" name="text" id="create_text_textarea" cols="30" rows="5"
              @class([
    'filament-forms-textarea-component filament-forms-input block w-full transition duration-75 rounded-lg shadow-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70',
    'border-gray-300' => ! $errors->has('text'),
    'border-danger-600 ring-danger-600' => $errors->has('text'),
])
    ></textarea>
    @error('text') <span
            class="filament-forms-field-wrapper-error-message text-sm text-danger-600">{{ $message }}</span> @enderror

    <div class="mt-4 flow-root">
        <div class="float-left">
            <div class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 bg-white border-gray-300 hover:bg-gray-50 focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600 filament-page-button-action">
                <a href="#" wire:click.prevent="addText(' {domain} ')" wire:loading.attr="disabled">
                    <span class="">Add shortlink</span>
                </a>
            </div>
        </div>

        <div class="float-right" x-data="{ show_other: false }">
            <div class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 bg-white border-gray-300 hover:bg-gray-50 focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600 filament-page-button-action">
                <a href="#" wire:click.prevent="addText(' {fname} ')">
                    <span class="">First name</span>
                </a>
            </div>
            <div class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 bg-white border-gray-300 hover:bg-gray-50 focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600 filament-page-button-action">
                <a href="#" x-on:click.prevent="show_other = !show_other;">
                    <span class="">Other</span>
                </a>
            </div>
            <div class="mt-3" x-show="show_other">
                <div class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 bg-white border-gray-300 hover:bg-gray-50 focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600 filament-page-button-action">
                    <a href="#" wire:click.prevent="addText('{d}')">
                        <span class="">Random digit</span>
                    </a>
                </div>
            </div>

        </div>

    </div>
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