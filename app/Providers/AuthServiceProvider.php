<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\DataFile;
use App\Models\SmsCampaignText;
use App\Policies\DataFilePolicy;
use App\Policies\SmsCampaignTextPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        SmsCampaignText::class => SmsCampaignTextPolicy::class,
        DataFile::class => DataFilePolicy::class,
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') .
                "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
