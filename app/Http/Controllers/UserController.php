<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $data = User::with('roles')->latest('id');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    $test = '';
                    foreach ($row->getRoleNames() as $roles) {

                        $test .= "<label class='badge badge-primary'>" . $roles . "</label>";

                    }
                    return '<div class="d-flex flex-wrap gap-2">'.$test.'</div>';
                })
                ->addColumn('action', function ($row) {
                    $actions = [];
                    $actions['edit'] = route('users.edit', $row->id);
                    $actions['destroy'] = $row->id;
                    return view('layouts.button', $actions);
                }) 
                ->addColumn('created', function ($row) {
                    return Carbon::parse($row->created_at)->timezone('Asia/Jakarta')->locale('id_ID')->isoFormat('DD MMMM Y'); 
                })
                ->rawColumns(['role', 'action', 'created'])
                ->make(true);
        }
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required',
            'email'     => 'required|unique:users,email',
            'password'  => 'required',
            'role'      => 'required'
        ]);

        try{

            DB::beginTransaction();
                User::create([
                    'name'      => $validated['name'],
                    'email'  => $validated['email'],
                    'email_verified_at' => Carbon::now(),
                    'password'  => bcrypt($validated['password']),
                ]);

                $roles  = $request->input('role');
                $userku = User::where('email', $request->input('email'))->first();
                $userku->assignRole($roles);

            DB::commit();

            $notification = array(
                'success'   => 'Berhasil tambah user '.$validated['name'],
            );

            return redirect()->route('users.index')->with($notification);

        } catch (\Throwable $e) {
            return redirect()->route('users.index')->with(['error' => 'Tambah data gagal! ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roles = Role::all();
        $detail = User::find($id);

        return view('users.edit', compact('roles', 'detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validated = $request->validate([
            'name'  => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'role'  => 'required'
        ]);

        try{
            
            $detail = User::findOrFail($id);

            DB::beginTransaction();
                $detail->update([
                    'name'      => $validated['name'],
                    'email'     => $validated['email'],
                ]);

                $detail->syncRoles($request->input('role'));                

            DB::commit();

            $notification = array(
                'success'   => 'Berhasil update user '.$validated['name'],
            );

            return redirect()->route('users.index')->with($notification);

        } catch (\Throwable $e) {
            return redirect()->route('users.index')->with(['error' => 'Tambah data gagal! ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $users =  User::find($id);
            if ($users) {
                $users->delete();
            }

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
