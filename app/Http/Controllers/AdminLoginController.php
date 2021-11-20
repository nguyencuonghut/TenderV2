<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    protected $redirectTo = '/admin';

    public function __construct()
    {
      $this->middleware('guest')->except('logout');
    }

    public function guard()
    {
     return Auth::guard('admin');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function handleLogin(Request $req)
    {
        if(Auth::guard('admin')->attempt($req->only(['email', 'password']))) {
            return redirect()->route('admin.home');
        }

        return back()->withErrors([
            'email' => 'Email hoặc password không đúng!',
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
