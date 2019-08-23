<?php

namespace App\Providers;

use Ramsey\Uuid\Uuid;
use App\ModelObservers;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use App\Macros\UploadedFileMacros;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use ModelObservers;
    use UploadedFileMacros;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();

        // Try to call the `register...` method on included traits.
        foreach (class_uses($this) as $trait) {
            $classname = class_basename($trait);

            if (method_exists($trait, 'register'.$classname)) {
                $trait::{'register'.$classname}();
            }
        }

        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }

        require __DIR__.'/../Support/helpers.php';
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerModelObservers();

        Client::creating(function (Client $client) {
            $client->incrementing = false;
            $client->id = Uuid::uuid4()->toString();
        });
    }

    /**
     * Register the model observers.
     *
     * @return void
     */
    public function registerModelObservers(): void
    {
        foreach ($this->observers as $model => $observer) {
            $model::observe($observer);
        }
    }
}
