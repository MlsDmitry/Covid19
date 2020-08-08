<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferencesController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        $pref = $user->preferences();
        $pref->update($request->all());
        return response()->setStatusCode(201);
    }

    public function index()
    {
        $pref = Auth::user()->preferences()->get();
        return response()->json(compact('pref'));
    }
}
