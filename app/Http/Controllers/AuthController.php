<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect(Auth::user()->role === 'admin' ? '/admin/dashboard' : '/user/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Fleksibel: Mendukung (Name + Email + Password), (Email + Password), atau (Name + Password)
        $rules = ['password' => 'required'];
        $messages = ['password.required' => 'Password wajib diisi.'];

        if ($request->has('name') && !$request->has('email')) {
            $rules['name'] = 'required|string';
            $messages['name.required'] = 'Nama lengkap wajib diisi.';
        } elseif ($request->has('email') && !$request->has('name')) {
            $rules['email'] = 'required|email';
            $messages['email.required'] = 'Alamat email wajib diisi.';
            $messages['email.email'] = 'Format email tidak valid.';
        } else {
            // Default: kedua input ada
            $rules['name'] = 'required|string';
            $rules['email'] = 'required|email';
            $messages['name.required'] = 'Nama lengkap wajib diisi.';
            $messages['email.required'] = 'Alamat email wajib diisi.';
            $messages['email.email'] = 'Format email tidak valid.';
        }

        $request->validate($rules, $messages);

        // Ambil user berdasarkan input yang tersedia
        if ($request->filled('name') && $request->filled('email')) {
            $user = User::where('name', $request->name)
                        ->where('email', $request->email)
                        ->first();
            if (!$user) {
                return back()->withInput($request->only('name', 'email'))
                             ->with('error', 'Kombinasi Nama dan Email tidak terdaftar dalam sistem.');
            }
        } elseif ($request->filled('email')) {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->withInput($request->only('email'))
                             ->with('error', 'Alamat email tidak terdaftar dalam sistem.');
            }
        } else {
            $user = User::where('name', $request->name)->first();
            if (!$user) {
                return back()->withInput($request->only('name'))
                             ->with('error', 'Nama pengguna tidak terdaftar dalam sistem.');
            }
        }

        if ($user->is_active == 0) {
            return back()->with('error', 'Akun Anda telah dinonaktifkan oleh Administrator. Akses ke sistem PustakaMart dibatasi.');
        }

        // Kredensial untuk Auth::attempt
        $credentials = ['password' => $request->password];
        if ($request->filled('email')) {
            $credentials['email'] = $request->email;
        } else {
            $credentials['name'] = $request->name;
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect(Auth::user()->role === 'admin' ? '/admin/dashboard' : '/user/dashboard')
                   ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        return back()->withInput($request->only('name', 'email'))
                     ->with('error', 'Password yang Anda masukkan salah.');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|min:10|max:15',
            'address' => 'required|string|min:10',
            'password' => 'required|min:6|confirmed',
            'nik' => $request->has('nik') ? 'required|string|min:16|max:16' : 'nullable',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.max' => 'NIK maksimal 16 digit.',
            'nik.min' => 'NIK minimal 16 digit.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'phone.min' => 'Nomor WhatsApp / HP minimal 10 digit.',
            'phone.max' => 'Nomor WhatsApp / HP maksimal 15 digit.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'address.min' => 'Alamat lengkap minimal 10 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'nik' => $request->nik,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_active' => 1,
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login dengan akun baru Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}