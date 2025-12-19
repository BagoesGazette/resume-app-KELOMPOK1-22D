<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JobOpening::with('apply')->latest('id');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->status === 'open') {
                        return "<label class='badge badge-success'>Buka</label>";
                    }else{
                        return "<label class='badge badge-danger'>Tutup</label>";
                    }
                })
                ->addColumn('total_pelamar', function ($row) {
                    return $row->apply->count();
                })
                ->addColumn('action', function ($row) {
                    $actions = [];
                    $actions['edit'] = route('job.edit', $row->id);
                    $actions['destroy'] = $row->id;
                    $actions['detail'] = route('job.show', $row->id);
                    return view('layouts.button', $actions);
                }) 
                ->rawColumns(['action', 'status', 'total_pelamar'])
                ->make(true);
        }
        return view('job.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('job.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'judul'     => 'required',
            'perusahaan'=> 'required',
            'lokasi'    => 'required',
            'deskripsi' => 'nullable',
            'tipe'      => 'nullable',
            'tanggal_tutup' => 'nullable',
            'category' => 'required',
        ]);

        try{

            DB::beginTransaction();
                JobOpening::create($validated);

            DB::commit();

            $notification = array(
                'success'   => 'Berhasil tambah lowongan kerja '.$validated['judul'],
            );

            return redirect()->route('job.index')->with($notification);

        } catch (\Throwable $e) {
            return redirect()->route('job.index')->with(['error' => 'Tambah data gagal! ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = JobOpening::find($id);

        
        return view('job.show', compact('detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $detail = JobOpening::find($id);

        
        return view('job.edit', compact('detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'judul'     => 'required',
            'perusahaan'=> 'required',
            'lokasi'    => 'required',
            'deskripsi' => 'nullable',
            'tipe'      => 'nullable',
            'tanggal_tutup' => 'nullable',
            'category' => 'required',
        ]);

        try{
            $detail = JobOpening::find($id);
            DB::beginTransaction();
                $detail->update($validated);

            DB::commit();

            $notification = array(
                'success'   => 'Berhasil update lowongan kerja '.$validated['judul'],
            );

            return redirect()->route('job.index')->with($notification);

        } catch (\Throwable $e) {
            return redirect()->route('job.index')->with(['error' => 'Tambah data gagal! ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data =  JobOpening::find($id);
            if ($data) {
                $data->delete();
            }

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function close($id)
    {
        try {
            $data =  JobOpening::find($id);
            if ($data) {
                $data->update([
                    'status' => 'closed'
                ]);
            }

            $notification = array(
                'success'   => 'Berhasil menutup lowongan kerja '.$data->judul,
            );

            return redirect()->back()->with($notification);
        } catch (\Throwable $e) {
            return redirect()->back()->with(['error' => 'Data gagal! ' . $e->getMessage()]);
        }
    }
}
