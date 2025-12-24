<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class PelamarController extends Controller
{
    public function show($id)
    {
        $application = JobApplication::find(1);

        return view('pelamar.detail', compact('application'));
    }
}
