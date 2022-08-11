<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

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
        if(Auth::guard('web')->attempt(['email' => $req->email, 'password' => $req->password], true)) {
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


    public function showForgotPasswordForm()
    {
        return view('user.forgot_password');
    }

    public function submitForgotPasswordForm(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users',
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

        Notification::route('mail' , $request->email)->notify(new UserForgotPassword($request->email, $token));

        return back()->with('flash_message_success', 'Chúng tôi vừa gửi đường link cấp lại mật khẩu tới email của bạn!');
    }

    public function showResetPasswordForm($token)
    {
        return view('user.recover_password', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users',
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

        $admin = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('flash_message_success', 'Mật khẩu được khôi phục thành công. Bạn hãy đăng nhập với mật khẩu mới!');
    }
}
