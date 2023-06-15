<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SmsCampaignsController;
use App\Http\Controllers\SmsRouteCompaniesController;
use App\Http\Controllers\SmsRoutingRoutesController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware([
    'auth',
//    config('jetstream.auth_session'),
//    'verified'
])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('home');

    Route::get('/campaigns', [SmsCampaignsController::class, 'index'])->name('campaigns.index');

    Route::get('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::prefix('sms')->name('sms.')->group(function () {
        Route::prefix('routing')->name('routing.')->group(function () {
            Route::resource('companies', SmsRouteCompaniesController::class);

            Route::resource('routes', SmsRoutingRoutesController::class);
            Route::post('routes/test-smpp-connection',
                [SmsRoutingRoutesController::class, 'testSmppConnection'])
                ->name('routes.test-smpp-connection');
        });
    });


    Route::group(['prefix' => 'api/v1'], function () {
        Route::post('/sms/routing/routes/test-smpp-connection',
            [SmsRoutingRoutesController::class, 'testSmppConnection'])
            ->name('sms.routing.routes.test-smpp-connection');

        Route::post('/sms/routing/companies',
            [\App\Http\Controllers\Api\V2\SmsRouteCompaniesController::class, 'store'])
            ->name('sms.routing.routes.store');
    });
});

//Route::group([
//    'middleware' => 'api',
//    'prefix' => 'auth'
//], function ($router) {
//
//    Route::post('login', 'AuthController@login');
//    Route::post('logout', 'AuthController@logout');
//    Route::post('refresh', 'AuthController@refresh');
//    Route::post('me', 'AuthController@me');
//
//});