<?php

use App\Http\Controllers\AppController;
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

Route::middleware(\App\Http\Middleware\RedirectIfAuthenticated::class)
    ->group(function () {
        Route::get('/login', [\App\Http\Controllers\AuthController::class, 'view'])->name('login');
        Route::get('/signup', [\App\Http\Controllers\AuthController::class, 'create'])->name('signup');
        Route::post('/signup', [\App\Http\Controllers\AuthController::class, 'store'])->name('signup.store');
    });

Route::middleware([
    'auth',
//    config('jetstream.auth_session'),
//    'verified'
])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('home');

    Route::get('/campaigns', [\App\Http\Controllers\SmsCampaignsController::class, 'index'])->name('campaigns.index');

    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'destroy'])->name('logout');

    Route::prefix('sms')->name('sms.')->group(function(){
        Route::prefix('routing')->name('routing.')->group(function(){
            Route::resource('companies', \App\Http\Controllers\SmsRouteCompanyController::class);
            Route::resource('routes', \App\Http\Controllers\SmsRoutingRoutesControllesController::class);
        });
    });
});