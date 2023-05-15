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
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}').defer }">

        <textarea
                {!! ($autocapitalize = $getAutocapitalize()) ? "autocapitalize=\"{$autocapitalize}\"" : null !!}
                {!! ($autocomplete = $getAutocomplete()) ? "autocomplete=\"{$autocomplete}\"" : null !!}
                {!! $isAutofocused() ? 'autofocus' : null !!}
                {!! $isDisabled() ? 'disabled' : null !!}
                id="{{ $getId() }}"
                dusk="filament.forms.{{ $getStatePath() }}"
                {!! ($placeholder = $getPlaceholder()) ? "placeholder=\"{$placeholder}\"" : null !!}
                {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
                @if (! $isConcealed())
                    {!! filled($length = $getMaxLength()) ? "maxlength=\"{$length}\"" : null !!}
                    {!! filled($length = $getMinLength()) ? "minlength=\"{$length}\"" : null !!}
                    {!! $isRequired() ? 'required' : null !!}
                @endif
                {{
                    $attributes
                        ->merge($getExtraAttributes())
                        ->merge($getExtraInputAttributeBag()->getAttributes())
                        ->class([
                            'filament-forms-textarea-component filament-forms-input block w-full transition duration-75 rounded-lg shadow-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70',
                            'dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-primary-500' => config('forms.dark_mode'),
                            'border-gray-300' => ! $errors->has($getStatePath()),
                            'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
                            'dark:border-danger-400 dark:ring-danger-400' => $errors->has($getStatePath()) && config('forms.dark_mode')
                        ])
                }}
{{--                @if ($shouldAutosize())--}}
                @if (1)
                    x-data="textareaFormComponent()"
                    x-on:input="render()"
                    style="height: 150px"
                    {{ $getExtraAlpineAttributeBag() }}
                @endif
        ></textarea>

        
    </div>
</x-dynamic-component>
