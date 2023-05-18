<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getHeader() : \Illuminate\Contracts\View\View|null
    {
        return view('filament.debug', [
        ]);
    }
}
