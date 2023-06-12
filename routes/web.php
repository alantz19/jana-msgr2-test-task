<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SmsCampaignsController;
use App\Http\Controllers\SmsRouteCompanyController;
use App\Http\Controllers\SmsRoutingRoutesControllesController;
use App\Http\Middleware\RedirectIfAuthenticated;
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

Route::middleware(RedirectIfAuthenticated::class)
    ->group(function () {
        Route::get('/login', [AuthController::class, 'view'])->name('login');
        Route::get('/signup', [AuthController::class, 'create'])->name('signup');
        Route::post('/signup', [AuthController::class, 'store'])->name('signup.store');
    });

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
            Route::resource('companies', SmsRouteCompanyController::class);

            Route::resource('routes', SmsRoutingRoutesControllesController::class);
            Route::post('routes/test-smpp-connection',
                [SmsRoutingRoutesControllesController::class, 'testSmppConnection'])
                ->name('routes.test-smpp-connection');
        });
    });
});