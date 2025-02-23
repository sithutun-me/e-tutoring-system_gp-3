<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PDO;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // $credentials = $request->only('email', 'password');
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role_id === 3) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role_id === 2) {
                return redirect()->route('tutor.dashboard');
            } elseif ($user->role_id === 1) {
                return redirect()->route('student.dashboard');
            }
        }

        throw ValidationException::withMessages([
            'credentials' => 'Sorry, incorrect credentials'
        ]);
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

