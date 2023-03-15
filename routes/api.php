<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('admin/reports/wizard-stats')->group(function () {
    Route::get('/estimators', 'Reports_WizardStatsController@getAll');
    Route::get('/estimators/{id}', 'Reports_WizardStatsController@getById');
    Route::post('/estimators', 'Reports_WizardStatsController@getById');
    Route::put('/estimators/{id}', 'Reports_WizardStatsController@gcreate');
    Route::delete('/estimators/{id}', 'Reports_WizardStatsController@gcreate');
});