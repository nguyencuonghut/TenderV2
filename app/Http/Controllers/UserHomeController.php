<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Tender;
use App\Models\TenderSuppliersSelectedStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class UserHomeController extends Controller
{
    public function index()
    {
        $tenders =  Tender::where('status', '<>', 'Mở')->get();
        $my_all_tenders = $tenders->filter(function($item, $key) {
            $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $item->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
            $my_supplier_id = Auth::user()->supplier_id;
            if(in_array($my_supplier_id, $selected_supplier_ids)) {
                return $item;
            }
        });

        $closed_tenders =  Tender::where('status', 'Đóng')->get();
        $my_completed_tenders = $closed_tenders->filter(function($item, $key) {
            $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $item->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
            $my_supplier_id = Auth::user()->supplier_id;
            if(in_array($my_supplier_id, $selected_supplier_ids)) {
                return $item;
            }
        });

        $in_progress_tenders =  Tender::where('status', 'Đang diễn ra')->get();
        $my_in_progress_tenders = $in_progress_tenders->filter(function($item, $key) {
            $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $item->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
            $my_supplier_id = Auth::user()->supplier_id;
            if(in_array($my_supplier_id, $selected_supplier_ids)) {
                return $item;
            }
        });

        $checking_tenders =  Tender::where('status', 'Đang kiểm tra')->get();
        $my_checking_tenders = $checking_tenders->filter(function($item, $key) {
            $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $item->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
            $my_supplier_id = Auth::user()->supplier_id;
            if(in_array($my_supplier_id, $selected_supplier_ids)) {
                return $item;
            }
        });

        return view('user.home', ['my_all_tenders' => $my_all_tenders,
                                    'my_completed_tenders' =>$my_completed_tenders,
                                    'my_in_progress_tenders' =>$my_in_progress_tenders,
                                    'my_checking_tenders' => $my_checking_tenders
                                ]);
    }

    public function profile()
    {
        $tenders =  Tender::where('status', '<>', 'Mở')->get();
        $my_tenders = $tenders->filter(function($item, $key) {
            $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $item->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
            $my_supplier_id = Auth::user()->supplier_id;
            if(in_array($my_supplier_id, $selected_supplier_ids)) {
                return $item;
            }
        });
        $tenders_cnt = $my_tenders->count();

        $my_bids = Bid::where('user_id', Auth::user()->id)->get();
        $bids_cnt = $my_bids->count();

        $my_selected_bids = Bid::where('user_id', Auth::user()->id)->where('is_selected', 1)->get();
        $selected_bids_cnt = $my_selected_bids->count();

        $recent_bids = Bid::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->take(3)->get();
        return view('user.profile', ['tenders_cnt' => $tenders_cnt,
                                     'bids_cnt' => $bids_cnt,
                                     'selected_bids_cnt' => $selected_bids_cnt,
                                     'recent_bids' => $recent_bids
                                    ]);
    }

    public function showChangePasswordForm()
    {
        return view('user.change_password');
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

        $user = User::findOrFail(Auth::user()->id);
        $user->password = bcrypt($request->password);
        $user->save();

        Alert::toast('Đổi mật khẩu thành công!', 'success', 'top-right');
        return redirect()->route('user.profile');

    }
}
