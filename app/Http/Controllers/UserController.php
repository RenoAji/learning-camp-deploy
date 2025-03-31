<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function viewRegister()
    {
        return view('auth.register');
    }

    function register(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'unique:users'],
            'email' => ['required', 'email:dns', 'unique:users'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['same:password']
        ]);
        
        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);
        //event(new Registered($user));

        return redirect()->route("login");
    }

    function viewLogin()
    {
        return view('auth.login');
    }

    function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials,$request->remember)) {
            $request->session()->regenerate();
 
            return redirect()->intended('/');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/');
    }
}
