<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            'rt' => ['nullable', 'string'],
        ]);

        $remember = $request->boolean('remember');

        $loginInput = $credentials['email'];
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$fieldType => $loginInput, 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            $rt = $request->input('rt', 'RT 04');
            session(['selected_rt' => $rt]);
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'login_error' => 'Username/Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Switch active RT session.
     */
    public function switchRt(Request $request)
    {
        $rt = $request->input('rt', 'RT 04');
        session(['selected_rt' => $rt]);
        return response()->json(['status' => 'success', 'rt' => $rt]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah berhasil keluar.');
    }
}
