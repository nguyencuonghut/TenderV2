<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    protected $redirectTo = '/';

    public function __construct()
    {
      $this->middleware('guest')->except('logout');
    }

    public function guard()
    {
     return Auth::guard('web');
    }

    public function showLoginForm()
    {
        return view('user.login');
    }

    public function handleLogin(Request $req)
    {
        if(Auth::guard('web')->attempt($req->only(['email', 'password']))) {
            return redirect()->route('user.home');
        }

        return back()->withErrors([
            'email' => 'Email hoặc password không đúng!',
        ]);
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('user.login');
    }
}
