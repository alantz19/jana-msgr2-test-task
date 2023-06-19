<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    die('welcome to the api');
});

//all apis are in "./api.php"

//Route::middleware([
//    'auth',
////    config('jetstream.auth_session'),
////    'verified'
//])->group(function () {
//
//    Route::get('/campaigns', [SmsCampaignsController::class, 'index'])->name('campaigns.index');
//
//    Route::get('/logout', [AuthController::class, 'destroy'])->name('logout');
//
//    Route::prefix('sms')->name('sms.')->group(function () {
//        Route::prefix('routing')->name('routing.')->group(function () {
//            Route::resource('companies', SmsRouteCompaniesController::class);
//
//            Route::resource('routes', SmsRoutingRoutesController::class);
//            Route::post('routes/test-smpp-connection',
//                [SmsRoutingRoutesController::class, 'testSmppConnection'])
//                ->name('routes.test-smpp-connection');
//        });
//    });
//});

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