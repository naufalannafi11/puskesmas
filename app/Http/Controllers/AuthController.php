<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)){
            $request->session()->regenerate();
            $role = Auth::user()->role;

            if ($role === 'admin'){
                return redirect('/admin');
            } elseif ($role==='dokter'){
                return redirect('/dokter');
            }else {
                return redirect('/pasien');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }

    public function showRegister()
{
    return view('auth.register');
}

public function register(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ]);

    // Ambil no_rm secara numerik tertinggi
    $lastUser = User::whereNotNull('no_rm')
        ->orderByRaw('CAST(no_rm AS UNSIGNED) DESC')
        ->first();

    $maxRM = $lastUser ? (int)$lastUser->no_rm : 0;

    // Generate no_rm baru (6 digit)
    $newRM = str_pad($maxRM + 1, 6, '0', STR_PAD_LEFT);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'pasien',
        'no_rm' => $newRM,
    ]);

    return redirect()->route('login')
        ->with('success', 'Pendaftaran berhasil, silakan login');
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
