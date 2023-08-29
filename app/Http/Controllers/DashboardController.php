<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function dashboard(): View
    {
        $userResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . auth()->user()->access_token
        ])
        ->get(config('services.mylogin.url') . '/api/user')
        ->json();


        return view('dashboard', [
            'userResponse' => $userResponse
        ]);
    }
}
