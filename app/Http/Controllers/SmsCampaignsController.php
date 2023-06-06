<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class SmsCampaignsController extends Controller
{
    public function index()
    {
        return Inertia::render('SmsCampaigns/Index');
    }
}
