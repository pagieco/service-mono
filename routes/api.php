<?php

Route::post('auth/authenticate', 'Auth\AuthenticateController')->name('authenticate');
Route::post('auth/register', 'Auth\RegistrationController')->name('register');
Route::get('auth/verify-email', 'Auth\VerificationController')->name('verification.verify')->middleware('signed');

Route::middleware(['auth:api', 'verified'])->group(function () {
    // Domain routes...
    Route::get('/domains', 'Domain\GetDomainsController')->name('get-domains');
    Route::get('/domains/{domain}', 'Domain\GetDomainController')->name('get-domain');

    // Font routes...
    Route::get('/font-list', 'Font\GetFontListController')->name('get-font-list');

    // Environment routes...
    Route::get('/environments', 'Environment\GetEnvironmentsController')->name('get-environments');
    Route::post('/environments', 'Environment\CreateEnvironmentsController')->name('create-environment');
    Route::put('/environments/{environment}/attach-domain', 'Environment\AttachDomainController')->name('attach-domain-to-environment');
    Route::delete('/environments/{environment}/detach-domain', 'Environment\DetachDomainController')->name('detach-domain-from-environment');
    Route::get('/environments/{environment}', 'Environment\GetEnvironmentController')->name('get-environment');
    Route::patch('/environments/{environment}', 'Environment\UpdateEnvironmentController')->name('update-environment');
    Route::delete('/environments/{environment}', 'Environment\DeleteEnvironmentController')->name('delete-environment');

    // Team routes...
    Route::get('/teams', 'Team\GetTeamsController')->name('get-teams');
    Route::get('/teams/{team}', 'Team\GetTeamController')->name('get-team');

    Route::prefix('domains/{domain}')->group(function () {
        // Asset routes...
        Route::get('/assets', 'Asset\GetAssetsController')->name('get-assets');

        // Form Routes...
        Route::get('/forms', 'Form\GetFormsController')->name('get-forms');

        // Page routes...
        Route::get('/pages', 'Page\GetPagesController')->name('get-pages');

        // Collection routes...
        Route::get('/collections', 'Collection\GetCollectionsController')->name('get-collections');
    });
});
