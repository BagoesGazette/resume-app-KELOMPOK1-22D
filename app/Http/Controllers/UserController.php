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
            
            // Filter by role
            if ($request->has('role') && $request->role != '') {
                $data->role($request->role);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_info', function ($row) {
                    $colors = ['#667eea', '#11998e', '#f7971e', '#eb3349', '#00c6fb', '#a855f7'];
                    $color = $colors[$row->id % count($colors)];
                    $initials = strtoupper(substr($row->name ?? 'U', 0, 2));
                    $photo = $row->photo 
                        ? '<img src="'.asset('storage/'.$row->photo).'" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">'
                        : $initials;
                    
                    return '
                    <div class="user-info-cell">
                        <div class="user-avatar" style="background: '.$color.';">
                            '.$photo.'
                        </div>
                        <div class="user-details">
                            <h6>'.$row->name.'</h6>
                            <small><i class="fas fa-envelope mr-1"></i> '.$row->email.'</small>
                        </div>
                    </div>';
                })
                ->addColumn('role', function ($row) {
                    $badges = '';
                    foreach ($row->getRoleNames() as $role) {
                        $roleClass = match(strtolower($role)) {
                            'admin' => 'admin',
                            'kandidat' => 'kandidat',
                            'hr' => 'hr',
                            default => 'default'
                        };
                        $badges .= '<span class="role-badge '.$roleClass.'">'.$role.'</span>';
                    }
                    return '<div class="role-badges">'.$badges.'</div>';
                })
                ->addColumn('status', function ($row) {
                    // Assuming there's an is_active column, if not we'll show as active
                    $isActive = $row->is_active ?? true;
                    if ($isActive) {
                        return '<span class="status-badge active"><i class="fas fa-check-circle mr-1"></i> Aktif</span>';
                    } else {
                        return '<span class="status-badge inactive"><i class="fas fa-times-circle mr-1"></i> Nonaktif</span>';
                    }
                })
                ->addColumn('created', function ($row) {
                    return '<span class="date-badge"><i class="fas fa-calendar mr-1"></i> '.Carbon::parse($row->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y').'</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <div class="action-buttons">
                        <a href="'.route('users.edit', $row->id).'" class="btn-action edit" data-toggle="tooltip" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn-action delete" data-toggle="tooltip" title="Hapus" onclick="Delete('.$row->id.')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>';
                })
                ->rawColumns(['user_info', 'role', 'status', 'action', 'created'])
                ->make(true);
        }

        // Statistics
        $stats = [
            'total' => User::count(),
            'admin' => User::role('admin')->count(),
            'kandidat' => User::role('kandidat')->count(),
            'thisMonth' => User::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count(),
        ];

        // Get all roles for filter
        $roles = Role::all();

        // Recent users
        $recentUsers = User::with('roles')
            ->latest()
            ->take(5)
            ->get();

        return view('users.index', compact('stats', 'roles', 'recentUsers'));
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
