<?php

use App\Http\Controllers\Api\V1\DataFilesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SmsRoutingCompaniesController;
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
        Route::post('logout', [AuthController::class, 'me']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::get('/data-files', [DataFilesController::class, 'index']);
        Route::get('/data-files/{id:uuid}/sample', [DataFilesController::class, 'sample']);
        Route::post('/data-files/contacts', [DataFilesController::class, 'uploadContacts']);
        Route::post('/data-files/{id:uuid}/import', [DataFilesController::class, 'startImport']);


        Route::prefix('sms')->name('sms.')->group(function () {
            Route::prefix('routing')->name('routing.')->group(function () {
                Route::resource('companies', SmsRoutingCompaniesController::class);
                Route::resource('routes', SmsRoutingRoutesController::class);
                Route::prefix('routes')->name('routes.')->group(function () {
                    Route::post('smpp-connections', [SmsRoutingSmppConnectionsController::class, 'store'])
                        ->name('smpp-connections.store');
                    Route::post('smpp-connections/test', [SmsRoutingSmppConnectionsController::class, 'test'])
                        ->name('smpp-connections.test');
                    Route::get('smpp-connections/{smpp_connection}/view',
                        [SmsRoutingSmppConnectionsController::class, 'show'])
                        ->name('smpp-connections.show');
                });

            });
        });

    });
});