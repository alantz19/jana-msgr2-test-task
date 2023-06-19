<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\DataFile;
use App\Models\SmsCampaignText;
use App\Policies\DataFilePolicy;
use App\Policies\SmsCampaignTextPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        SmsCampaignText::class => SmsCampaignTextPolicy::class,
        DataFile::class => DataFilePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
