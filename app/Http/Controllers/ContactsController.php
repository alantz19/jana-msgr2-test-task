<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactCollection;
use App\Models\Clickhouse\Views\ContactSms;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function index()
    {
        $contacts = ContactSms::where('team_id', auth()->user()->current_team_id);

        return new ContactCollection($contacts);
    }
}
