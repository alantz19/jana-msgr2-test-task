<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Concerns\CanBeAutocapitalized;
use Filament\Forms\Components\Concerns\CanBeAutocompleted;
use Filament\Forms\Components\Concerns\CanBeLengthConstrained;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;

class CreateSmsText extends Field
{
    use CanBeAutocapitalized;
    use CanBeAutocompleted;
    use CanBeLengthConstrained;
    use HasExtraInputAttributes;
    use HasPlaceholder;
    use HasExtraAlpineAttributes;

    protected string $view = 'forms.components.create-sms-text';
}