<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PasswordController extends Controller
{
    // Form lupa sandi — masukkan email
    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    // Verifikasi email & tampilkan form ganti sandi langsung
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak ditemukan di sistem.',
        ]);

        // Simpan email yang diverifikasi di session
        Session::put('reset_email', $request->email);

        return redirect()->route('password.reset.form')
            ->with('success', 'Email ditemukan. Silakan buat password baru.');
    }

    // Form ganti sandi
    public function resetForm()
    {
        if (!Session::has('reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi tidak valid. Silakan ulangi dari awal.');
        }

        $email = Session::get('reset_email');
        return view('auth.reset-password', compact('email'));
    }

    // Proses ganti sandi
    public function reset(Request $request)
    {
        if (!Session::has('reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi tidak valid. Silakan ulangi dari awal.');
        }

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ], [
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $email = Session::get('reset_email');
        $user  = User::where('email', $email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        Session::forget('reset_email');

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login.');
    }
}
