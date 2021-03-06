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
        $tenders =  Tender::where('status', '<>', 'Open')->get();
        $my_all_tenders = $tenders->filter(function($item, $key) {
            $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $item->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
            $my_supplier_id = Auth::user()->supplier_id;
            if(in_array($my_supplier_id, $selected_supplier_ids)) {
                return $item;
            }
        });

        $closed_tenders =  Tender::where('status', 'Closed')->get();
        $my_completed_tenders = $closed_tenders->filter(function($item, $key) {
            $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $item->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
            $my_supplier_id = Auth::user()->supplier_id;
            if(in_array($my_supplier_id, $selected_supplier_ids)) {
                return $item;
            }
        });

        $in_progress_tenders =  Tender::where('status', 'In-progress')->get();
        $my_in_progress_tenders = $in_progress_tenders->filter(function($item, $key) {
            $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $item->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
            $my_supplier_id = Auth::user()->supplier_id;
            if(in_array($my_supplier_id, $selected_supplier_ids)) {
                return $item;
            }
        });

        $my_user_id = Auth::user()->id;
        $my_bids = Bid::where('user_id', $my_user_id)->get();

        return view('user.home', ['my_all_tenders' => $my_all_tenders,
                                    'my_completed_tenders' =>$my_completed_tenders,
                                    'my_in_progress_tenders' =>$my_in_progress_tenders,
                                    'my_bids' => $my_bids
                                ]);
    }

    public function profile()
    {
        $my_tenders = collect();
        $tenders = Tender::with('material')->where('status', '<>', 'Open')->orderBy('id', 'desc')->select(['id', 'title', 'material_id', 'tender_start_time', 'tender_end_time', 'status'])->get();
        foreach($tenders as $tender) {
            $selected_supplier_ids = [];
            $selected_supplier_ids = explode(",", $tender->supplier_ids);
            if(in_array(Auth::user()->supplier_id, $selected_supplier_ids)) {
                $my_tenders->push($tender);
            }
        }
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
            'password.required' => 'B???n ph???i nh???p m???t kh???u.',
            'password.min' => 'M???t kh???u ph???i d??i ??t nh???t 6 k?? t???.',
            'password.confirmed' => 'M???t kh???u kh??ng kh???p.',
        ];
        $request->validate($rules,$messages);

        $user = User::findOrFail(Auth::user()->id);
        $user->password = bcrypt($request->password);
        $user->save();

        Alert::toast('?????i m???t kh???u th??nh c??ng!', 'success', 'top-right');
        return redirect()->route('user.profile');

    }
}
