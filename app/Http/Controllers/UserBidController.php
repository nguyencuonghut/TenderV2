<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Tender;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class UserBidController extends Controller
{
    public function index($tender_id)
    {
        $tender = Tender::findOrFail($tender_id);
        $selected_supplier_ids = [];
        $selected_supplier_ids = explode(",", $tender->supplier_ids);
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();
        if(in_array(Auth::user()->id, $users)) {
            $bids = Bid::where('tender_id', $tender_id)->where('user_id', Auth::user()->id)->get();
            return view('user.bid.index', ['bids' => $bids, 'tender' => $tender]);
        } else {
            Alert::toast('Bạn không quyền bỏ thầu tender này!', 'error', 'top-right');
            return redirect()->route('user.tenders.index');
        }
    }

    public function destroy($id)
    {
        $bid = Bid::findOrFail($id);
        $selected_supplier_ids = [];
        $selected_supplier_ids = explode(",", $bid->tender->supplier_ids);
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();

        if('Closed' != $bid->tender->status
            && Carbon::now()->lessThan($bid->tender->tender_end_time)
            && in_array(Auth::user()->id, $users)) {
            $bid->destroy($id);
            Alert::toast('Xóa thành công!', 'success', 'top-right');
        } else {
            Alert::toast('Tender đã hết hạn. Không thể xóa!', 'error', 'top-right');
        }
        return redirect()->back();
    }

    public function create(Request $request, $tender_id)
    {
        $tender = Tender::findOrFail($tender_id);
        $selected_supplier_ids = [];
        $selected_supplier_ids = explode(",", $tender->supplier_ids);
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();
        if('Closed' != $tender->status
            && Carbon::now()->lessThan($tender->tender_end_time)
            && in_array(Auth::user()->id, $users)) {
            $rules = [
                'quantity' => 'required',
                'quantity_unit' => 'required',
                'price' => 'required',
                'price_unit' => 'required',
            ];
            $messages = [
                'quantity.required' => 'Bạn phải nhập số lượng.',
                'quantity_unit.required' => 'Bạn phải chọn đơn vị.',
                'price.required' => 'Bạn phải nhập giá.',
                'price_unit.required' => 'Bạn phải chọn loại tiền tệ.',
            ];
            $request->validate($rules,$messages);

            $bid = new Bid();
            $bid->tender_id = $tender_id;
            $bid->user_id = Auth::user()->id;
            $bid->quantity = $request->quantity;
            $bid->quantity_unit = $request->quantity_unit;
            $bid->price = $request->price;
            $bid->price_unit = $request->price_unit;
            $bid->pack = $request->pack;
            $bid->delivery_time = $request->delivery_time;
            $bid->delivery_place = $request->delivery_place;
            $bid->payment_condition = $request->payment_condition;
            $bid->note = $request->note;
            $bid->save();

            Alert::toast('Tạo mới thành công!', 'success', 'top-right');
            return redirect()->route('user.bids.index', $tender_id);
        } else {
            Alert::toast('Tender đã hết hạn!', 'error', 'top-right');
            return redirect()->route('user.tenders.index');
        }
    }
}
