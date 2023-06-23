<?php

use App\Http\Controllers\Api\V1\DataFilesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\MobileNetworksController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\SmsRoutingCompaniesController;
use App\Http\Controllers\SmsRoutingPlanRulesController;
use App\Http\Controllers\SmsRoutingPlansController;
use App\Http\Controllers\SmsRoutingRatesController;
use App\Http\Controllers\SmsRoutingRoutesController;
use App\Http\Controllers\SmsRoutingSmppConnectionsController;
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

Route::prefix('v1')->group(function () {

    Route::prefix('token')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'me']);

        Route::get('/data-files/{id:uuid}/sample', [DataFilesController::class, 'sample']);
        Route::post('/data-files/contacts', [DataFilesController::class, 'uploadContacts']);
        Route::post('/data-files/{id:uuid}/import', [DataFilesController::class, 'startImport']);

        Route::get('countries', [CountriesController::class, 'index']);

        Route::prefix('sms')->name('sms.')->group(function () {
            Route::prefix('routing')->name('routing.')->group(function () {

                Route::resource('networks', MobileNetworksController::class)->only(['index']);
                Route::resource('companies', SmsRoutingCompaniesController::class)
                    ->only(['index', 'store']);

                Route::resource('routes', SmsRoutingRoutesController::class)
                    ->only(['index', 'store', 'destroy']);

                Route::prefix('routes')->name('routes.')->group(function () {
                    Route::post('smpp-connections', [SmsRoutingSmppConnectionsController::class, 'store'])
                        ->name('smpp-connections.store');
                    Route::post('smpp-connections/test', [SmsRoutingSmppConnectionsController::class, 'test'])
                        ->name('smpp-connections.test');
                    Route::get('smpp-connections/{smpp_connection}/view',
                        [SmsRoutingSmppConnectionsController::class, 'show'])
                        ->name('smpp-connections.show');
                });

                Route::group(['prefix' => 'plans/{plan}', 'as' => 'plans.'], function () {
                    Route::resource('rules', SmsRoutingPlanRulesController::class)
                        ->only(['index', 'store', 'destroy', 'update', 'show']);
                });

                Route::resource('plans', SmsRoutingPlansController::class)
                    ->only(['index', 'store', 'destroy', 'update', 'show']);

                Route::resource('rates', SmsRoutingRatesController::class)->only(['store', 'index', 'update']);
                Route::get('rates/logs', [SmsRoutingRatesController::class, 'logs'])->name('rates.logs');
            });

        });

        Route::resource('offers', OffersController::class)->only(['index', 'store', 'update',
            'destroy']);
    });
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});