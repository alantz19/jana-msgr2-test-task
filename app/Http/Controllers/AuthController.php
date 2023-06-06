<?php

namespace App\Http\Controllers;


use App\Data\LoginData;
use App\Data\SignupData;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function view()
    {
        return Inertia::render('Auth/Login', ['formData' => LoginData::empty()]);
    }

    public function login(LoginData $data)
    {
        if (\Auth::guard('web')->attempt($data->toArray(), $data->remember)) {
            return to_route('home');
        };

        return Inertia::render('Auth/Login', [
            'formData' => $data,
            'errors' => [
                'auth' => 'The provided credentials do not match our records.',
            ],
        ]);
    }

    public function destroy(Request $request)
    {
        \Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function create()
    {
        return Inertia::render('Auth/Signup', [
            'formData' => \App\Data\SignupData::empty()
        ]);
    }

    public function store(\App\Data\SignupData $data)
    {
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
    }
}
