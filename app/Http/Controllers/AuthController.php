<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $request->session()->flash('success', 'Selamat datang, ' . Auth::user()->name . '!');

            // Simpan preferensi theme via cookie jika belum ada
            $theme = Cookie::get('theme', 'light');
            $response = redirect()->route('dashboard');

            if ($remember) {
                // Cookie remember email selama 30 hari
                $response = $response->withCookie(
                    Cookie::make('remembered_email', $request->email, 60 * 24 * 30)
                );
            }

            return $response;
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withCookie(Cookie::forget('remembered_email'))
            ->with('success', 'Anda telah logout.');
    }

    public function setTheme(Request $request)
    {
        $theme = $request->input('theme', 'light');
        return back()->withCookie(Cookie::make('theme', $theme, 60 * 24 * 365));
    }
}
