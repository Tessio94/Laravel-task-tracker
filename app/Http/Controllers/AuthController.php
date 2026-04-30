<?php

namespace App\Http\Controllers;

use App\Actions\Auth\RegisterUser;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function login(LoginRequest $request)
    {
        if(Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
        }

       return back()
        ->withErrors(['email' => 'These credentials do not match our records.'])
        ->withInput($request->except('password'));
    }

    public function register(RegisterRequest $request, RegisterUser $registerUser)
    {
        $user = $registerUser->execute($request->validated());

        Auth::login($user);

        return redirect()->intended(route('dashboard', absolute:false));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect("/");
    }
}
