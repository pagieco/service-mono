<?php

namespace App\Providers;

use App;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        App\Asset::class => App\Policies\AssetPolicy::class,
        App\Collection::class => App\Policies\CollectionPolicy::class,
        App\Domain::class => App\Policies\DomainPolicy::class,
        App\Environment::class => App\Policies\EnvironmentPolicy::class,
        App\Form::class => App\Policies\FormPolicy::class,
        App\Page::class => App\Policies\PagePolicy::class,
        App\Team::class => App\Policies\TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
