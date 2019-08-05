<?php

Route::group(['domain' => config('app.domain')], function () {
    // Catch-all resource routing for the backend.
    Route::get('/app/{resource?}', 'BackendController')->where('resource', '(.*)');
});

// Catch-all resource routing.
Route::get('{any?}', 'FrontendController')->where('any', '(.*)');
