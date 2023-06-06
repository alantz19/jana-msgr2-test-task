<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function destroy(Request $request)
    {
        \Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
