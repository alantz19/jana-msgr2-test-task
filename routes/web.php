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
        Route::get('/login', function () {
            return Inertia::render('Auth/Login');
        })->name('login');
        Route::get('/signup', function () {
            return Inertia::render('Auth/Signup', [
                'formData' => \App\Data\SignupData::empty()
            ]);
        })->name('signup');

        Route::post('/signup', function (\App\Data\SignupData $data) {
            $user = \App\Models\User::create($data->toArray());
            $team = \App\Models\Team::make([
                'user_id' => $user->id,
                'name' => $user->name,
                'personal_team' => false
            ]);
            $team->user_id = $user->id;
            $team->save();

            $user->current_team_id = $team->id;
            $user->save();
            \Auth::guard('web')->login($user);

            return to_route('home');
        });
    });

Route::middleware([
    'auth',
//    config('jetstream.auth_session'),
//    'verified'
])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('home');

    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'destroy'])
        ->name('logout');
});