<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $pengguna = User::role('kandidat')->count();
        if (Auth::user()->role('kandidat')) {
            return view('dashboard.kandidat');
        }else{
            return view('dashboard.index', compact('pengguna'));
        }
    }
}
