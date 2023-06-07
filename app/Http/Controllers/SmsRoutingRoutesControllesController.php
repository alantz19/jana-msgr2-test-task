<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class SmsRoutingRoutesControllesController extends Controller
{
    public function index()
    {
        return Inertia::render('Routing/Routes/Index',[
            'routes' => \App\Models\SmsRoute::where([
                'team_id' => auth()->user()->currentTeam->id
            ])->get()
        ]);
    }
}
