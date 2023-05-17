<?php

namespace App\Providers;

use App\Notifications\ModalNotification;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\ServiceProvider;
use Filament\Notifications\Notification as BaseNotification;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(ModalNotification::class);
        ModalNotification::configureUsing(function (Notification $notification): void {
            $notification->view('notifications.modal_notification');
        });
    }
}
