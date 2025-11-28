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
}