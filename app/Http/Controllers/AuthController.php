<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(){
        if (Auth::check()) {
            return redirect()->route('home');
        }else{
            return view('auth.login');
        }
    }

    public function loginProses(Request $request){
        $request->validate([
            'email'     => 'required',
            'password'  => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with(['error' => 'Email tidak ada!']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->with(['error' => 'Password salah!']);
        }

        if (empty($user->email_verified_at)) {
            return redirect()->back()->with(['error' => 'Email belum dikonfirmasi!']);
        }

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Berhasil login');
    }

    public function register(){
        
        return view('auth.register');
    }

    public function profile(){
        
        return view('auth.profile');
    }

    public function registerProses(Request $request)
    {
        $request->validate([
            'email'     => 'required|email|unique:users,email',
            'name'      => 'required|string|max:255',
            'password'  => 'required|min:6|confirmed',
        ]);

        try {
            $user = new User();
            $user->email = $request->input('email');
            $user->name = $request->input('name');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            $user->assignRole('kandidat');
            
            
            $this->checkEmail($user, $user->id);

            return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan check email.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function confirmEmail($id){
        $user = User::find($id);

        if ($user) {

            $user->email_verified_at = Carbon::now(); 
            $user->save();

            return redirect()->route('login')->with('success', 'Email berhasil dikonfirmasi, silakan login.');
        }else{
            return redirect()->route('login')->with('error', 'Email tidak ditemukan');
        }
    }

    private function checkEmail($user, $id){
        try {
            Mail::to($user->email)->send(new ConfirmEmail($user, $id));
            return true;
        } catch (\Exception $e) {
            return true;
        }
    }

    public function logout(){
 
        Auth::logout();

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email'     => 'required|email|unique:users,email,'.Auth::id(),
            'name'      => 'required|string|max:255',
        ]);


        $user->update($validated);

        return redirect()->route('profile.index')
            ->with('success', 'Profile berhasil diupdate.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => [
                'required',
                'confirmed'
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'new_password.min' => 'Password minimal 8 karakter.',
            'new_password.mixed_case' => 'Password harus mengandung huruf besar dan kecil.',
            'new_password.numbers' => 'Password harus mengandung angka.',
            'new_password.symbols' => 'Password harus mengandung simbol.',
            'new_password.uncompromised' => 'Password ini pernah bocor dalam data breach. Silakan gunakan password lain.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Password berhasil diubah.');
    }

}