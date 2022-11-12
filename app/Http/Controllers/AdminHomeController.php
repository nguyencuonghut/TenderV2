<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Supplier;
use App\Models\Tender;
use Database\Seeders\SuppliersTableSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AdminHomeController extends Controller
{
    public function index()
    {
        $all_tenders_count = Tender::count();
        $completed_tenders_count = Tender::where('status', 'Đóng')->count();
        $in_progress_tenders_count= Tender::where('status', 'Đang diễn ra')->count();
        $checking_tenders_count= Tender::where('status', 'Đang kiểm tra')->count();

        return view('admin.home', ['all_tenders_count' => $all_tenders_count,
                                    'completed_tenders_count' =>$completed_tenders_count,
                                    'in_progress_tenders_count' =>$in_progress_tenders_count,
                                    'checking_tenders_count' => $checking_tenders_count]);
    }

    public function profile()
    {
        $recent_tenders = Tender::where('creator_id', Auth::user()->id)->orWhere('auditor_id', Auth::user()->id)->orderBy('id', 'desc')->take(3)->get();
        $my_tenders = Tender::where('creator_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $my_tenders_cnt = $my_tenders->count();
        $my_audited_tenders = Tender::where('auditor_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $my_audited_tenders_cnt = $my_audited_tenders->count();
        return view('admin.profile', ['recent_tenders' => $recent_tenders,
                                      'my_tenders_cnt' => $my_tenders_cnt,
                                      'my_audited_tenders_cnt' => $my_audited_tenders_cnt
                                    ]);
    }

    public function showChangePasswordForm()
    {
        return view('admin.change_password');
    }

    public function submitChangePasswordForm(Request $request)
    {
        $rules = [
            'password' => 'required|confirmed|min:6',
        ];
        $messages = [
            'password.required' => 'Bạn phải nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải dài ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu không khớp.',
        ];
        $request->validate($rules,$messages);

        $admin = Admin::findOrFail(Auth::user()->id);
        $admin->password = bcrypt($request->password);
        $admin->save();

        Alert::toast('Đổi mật khẩu thành công!', 'success', 'top-right');
        return redirect()->route('admin.profile');

    }
}
