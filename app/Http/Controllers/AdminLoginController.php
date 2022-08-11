<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Notifications\AdminForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;


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
        if(Auth::guard('admin')->attempt(['email' => $req->email, 'password' => $req->password], true)) {
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

    public function showForgotPasswordForm()
    {
        return view('admin.forgot_password');
    }

    public function submitForgotPasswordForm(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:admins',
        ];
        $messages = [
            'email.required' => 'Bạn phải nhập địa chỉ email.',
            'email.email' => 'Email sai định dạng.',
            'email.exists' => 'Email không tồn tại trên hệ thống.',
        ];
        $request->validate($rules,$messages);

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
          ]);

        Notification::route('mail' , $request->email)->notify(new AdminForgotPassword($request->email, $token));

        return back()->with('flash_message_success', 'Chúng tôi vừa gửi đường link cấp lại mật khẩu tới email của bạn!');
    }

    public function showResetPasswordForm($token)
    {
        return view('admin.recover_password', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:admins',
            'password' => 'required|confirmed|min:6',
        ];
        $messages = [
            'email.required' => 'Bạn phải nhập địa chỉ email.',
            'email.email' => 'Email sai định dạng.',
            'email.exists' => 'Email không tồn tại',
            'password.required' => 'Bạn phải nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải dài ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu không khớp.',
        ];
        $request->validate($rules,$messages);

        $updatePassword = DB::table('password_resets')
                            ->where([
                            'email' => $request->email,
                            'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('flash_message_error', 'Token không hợp lệ!');
        }

        $admin = Admin::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect('/admin/login')->with('flash_message_success', 'Mật khẩu được khôi phục thành công. Bạn hãy đăng nhập với mật khẩu mới!');
    }
}
