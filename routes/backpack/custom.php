<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    // Custom Admin Dashboard
    Route::get('dashboard', 'DashboardController@dashboard')->name('backpack.dashboard');
    Route::get('/', 'DashboardController@dashboard')->name('backpack');

    // Master Data
    Route::crud('state', 'StateCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('shift', 'ShiftCrudController');
    Route::crud('exam-authority', 'ExamAuthorityCrudController');

    // Exams Management
    Route::crud('exam', 'ExamCrudController');
    Route::crud('exam-cycle', 'ExamCycleCrudController');
    Route::crud('exam-stage', 'ExamStageCrudController');

    // Prediction Configuration
    Route::crud('prediction-model', 'PredictionModelCrudController');
    Route::crud('cutoff', 'CutoffCrudController');
    Route::crud('vacancy', 'VacancyCrudController');

    // Public Content
    Route::crud('notice', 'NoticeCrudController');
    Route::crud('important-link', 'ImportantLinkCrudController');

    // Candidate / Prediction Data
    Route::crud('candidate-submission', 'CandidateSubmissionCrudController');
    Route::crud('prediction-result', 'PredictionResultCrudController');
    Route::crud('submission-risk-log', 'SubmissionRiskLogCrudController');

    // User Management
    Route::crud('user', 'UserCrudController');

}); // this should be the absolute last line of this file
