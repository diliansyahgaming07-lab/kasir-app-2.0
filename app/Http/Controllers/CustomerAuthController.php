<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CustomerAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.customer-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            Auth::login($user);

            if (Auth::check()) {
                return redirect('/')->with('success', '✅ Registrasi berhasil! Selamat berbelanja, ' . $user->name . '!');
            } else {
                return redirect('/login-customer')->with('error', 'Registrasi berhasil tapi gagal login. Silakan login manual.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showLogin()
    {
        return view('auth.customer-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = ['email' => $request->email, 'password' => $request->password, 'role' => 'customer'];
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/')->with('success', '🔐 Login berhasil! Selamat datang, ' . Auth::user()->name . '!');
        }

        return back()->withErrors(['email' => 'Email atau password salah!'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login-customer')->with('success', '👋 Anda telah logout.');
    }
}