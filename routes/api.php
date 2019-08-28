<?php

// Public auth routes....
Route::post('auth/authenticate', 'Auth\AuthenticateController')->name('authenticate');
Route::post('auth/register', 'Auth\RegistrationController')->name('register');
Route::get('auth/verify-email', 'Auth\VerificationController')->name('verification.verify')->middleware('signed');

// Public form routes...
Route::post('/forms/{form}/submit', 'Form\SubmitFormController')->name('submit-form');

// Public visitor routes...
Route::post('/visitor', 'Visitor\IdentifyVisitorController')->name('identify-visitor');

Route::middleware(['auth:api', 'verified'])->group(function () {
    // Auth routes...
    Route::get('/auth/current-user', 'Auth\CurrentUserController')->name('current-user');

    // User routes...
    Route::patch('/user', 'User\UpdateUserController')->name('update-user');
    Route::post('/user/upload-picture', 'User\UploadPictureController')->name('upload-user-picture');

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

    // Workflow routes...
    Route::get('/workflows', 'Workflow\GetWorkflowsController')->name('get-workflows');
    Route::post('/workflows', 'Workflow\CreateWorkflowController')->name('create-workflow');
    Route::get('/workflows/{workflow}', 'Workflow\GetWorkflowController')->name('get-workflow');
    Route::patch('/workflows/{workflow}', 'Workflow\UpdateWorkflowController')->name('update-workflow');
    Route::delete('/workflows/{workflow}', 'Workflow\DeleteWorkflowController')->name('delete-workflow');

//        Route::get('/workflow/{workflow}/steps', 'Workflow\GetWorkflowStepsController')->name('get-workflow-steps');
//        Route::post('/workflow/{workflow}/steps', 'Workflow\CreateWorkflowStepController')->name('create-workflow-step');
//        Route::get('/workflow/{workflow}/steps/{step}', 'Workflow\GetWorkflowStepController')->name('get-workflow-step');
//        Route::patch('/workflow/{workflow}/steps/{step}', 'Workflow\UpdateWorkflowStepController')->name('update-workflow-step');
//        Route::delete('/workflow/{workflow}/steps/{step}', 'Workflow\DeleteWorkflowStepController')->name('delete-workflow-step');

    Route::prefix('domains/{domain}')->group(function () {

        // Asset routes...
        Route::get('/assets', 'Asset\GetAssetsController')->name('get-assets');
        Route::post('/assets', 'Asset\UploadAssetController')->name('upload-asset');
        Route::get('/assets/{asset}', 'Asset\GetAssetController')->name('get-asset');
        Route::delete('/assets/{asset}', 'Asset\DeleteAssetController')->name('delete-asset');

        // Form Routes...
        Route::get('/forms', 'Form\GetFormsController')->name('get-forms');
        Route::post('/forms', 'Form\CreateFormController')->name('create-form');
        Route::get('/forms/{form}/submissions', 'Form\GetFormSubmissionsController')->name('get-form-submissions');

        // Automation Routes...
//        Route::get('/automations', 'Automation\GetAutomationsController')->name('get-automations');
//        Route::post('/automations', 'Automation\CreateAutomationController')->name('create-automation');
//        Route::get('/automations/{automation}', 'Automation\GetAutomationController')->name('get-automation');
//        Route::delete('/automations/{automation}', 'Automation\GetAutomationController')->name('get-automation');

        // Page routes...
        Route::get('/pages', 'Page\GetPagesController')->name('get-pages');
        Route::put('/pages/{page}/publish', 'Page\PublishPageController')->name('publish-page');

        // Collection routes...
        Route::get('/collections', 'Collection\GetCollectionsController')->name('get-collections');
        Route::post('/collections', 'Collection\CreateCollectionController')->name('create-collection');
        Route::get('/collections/{collection}', 'Collection\GetCollectionController')->name('get-collection');
        Route::delete('/collections/{collection}', 'Collection\DeleteCollectionController')->name('delete-collection');
        Route::get('/collections/{collection}/entries', 'Collection\GetCollectionEntriesController')->name('get-collection-entries');
    });
});
