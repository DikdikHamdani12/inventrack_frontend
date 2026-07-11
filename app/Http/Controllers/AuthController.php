<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $apiUrl = env('API_URL');
        $response = Http::post("{$apiUrl}/login", $credentials);

        if ($response->successful()) {
            $data = $response->json();
            
            if ($data['success']) {
                session(['api_token' => $data['data']['token']]);
                session(['user' => $data['data']['user']]);
                
                return redirect()->intended('/')->with('success', 'Selamat datang, ' . $data['data']['user']['name']);
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        $apiUrl = env('API_URL');
        $token = session('api_token');

        if ($token) {
            Http::withToken($token)->post("{$apiUrl}/logout");
        }

        session()->forget('api_token');
        session()->forget('user');
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
