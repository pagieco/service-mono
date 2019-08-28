<?php

namespace App\Providers;

use App\Listeners;
use Illuminate\Auth\Events\Registered;
use Laravel\Passport\Events\AccessTokenCreated;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            Listeners\CreatePersonalUserTeam::class,
            Listeners\CreateInitialDomainAndEnvironment::class,
            Listeners\FetchUsersGravatar::class,
        ],

        AccessTokenCreated::class => [
            Listeners\LogUserLogin::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
