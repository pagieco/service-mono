<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | The list of available authorization permissions. These will only be used
    | when seeding the database and should never directly be referred to.
    |
    */

    'permissions' => [
        'asset:list' => ['List assets', 'List the assets.'],
        'collection:list' => ['List collections', 'List the collections.'],
        'domain:list' => ['List domains', 'List the domains.'],
        'domain:view' => ['View domain', 'View a domain.'],
        'environment:list' => ['List environments', 'List the environments.'],
        'environment:create' => ['Create environment', 'Create a new environment.'],
        'environment:view' => ['View environment', 'View an environment.'],
        'environment:attach-domain' => ['Attach domain', 'Attach a domain to an environment.'],
        'environment:detach-domain' => ['Detach domain', 'Detach a domain from an environment.'],
        'form:list' => ['List forms', 'List the forms.'],
        'page:list' => ['List pages', 'List the pages.'],
        'team:list' => ['List teams', 'List the teams.'],
        'team:view' => ['View team', 'View a team.'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirect After Verify
    |--------------------------------------------------------------------------
    |
    | Where the user redirects to after successfully email verification.
    |
    */

    'redirect-after-verify' => env('AUTH_REDIRECT_AFTER_VERIFY'),

];
