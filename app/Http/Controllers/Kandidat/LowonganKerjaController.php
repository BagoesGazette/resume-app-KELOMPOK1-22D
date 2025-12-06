<?php

namespace App\Http\Controllers\Kandidat;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use Illuminate\Http\Request;

class LowonganKerjaController extends Controller
{
    public function index()
    {
        $job = JobOpening::where('status', 'open')->get();

        return view('kandidat.lowongan-kerja.index', compact('job'));
    }

    public function create($id)
    {
        $lowongan = JobOpening::find($id);

        return view('kandidat.lowongan-kerja.create', compact('lowongan'));
    }

    public function show($id)
    {
        $lowongan = JobOpening::find($id);

        return view('kandidat.lowongan-kerja.show', compact('lowongan'));
    }

    public function pelamar()
    {
        return view('kandidat.lowongan-kerja.detail-loker');
    }
}
