<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\QuantityAndDeliveryTime;
use App\Models\Tender;
use App\Models\TenderSuppliersSelectedStatus;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Notifications\BidCreatedOrUpdated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Datatables;
use Illuminate\Support\Facades\Notification;

class UserBidController extends Controller
{
    public function index($tender_id)
    {
        $tender = Tender::findOrFail($tender_id);

        $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();
        $existed_qty_ids = Bid::where('tender_id', $tender->id)->where('user_id', Auth::user()->id)->pluck('quantity_id')->toArray();
        $price_unit_arr = Bid::where('tender_id', $tender->id)->pluck('price_unit')->toArray();
        $unique = array_count_values($price_unit_arr);
        if($tender->is_competitive_bids
        && 1 == sizeof($unique)){
            $is_rating = true;
        }else{
            $is_rating = false;
        }
        if(in_array(Auth::user()->id, $users)) {
            $bids = Bid::where('tender_id', $tender_id)->where('user_id', Auth::user()->id)->get();
            $quantity_and_delivery_times = QuantityAndDeliveryTime::where('tender_id', $tender->id)->get();
            return view('user.bid.index', ['bids' => $bids,
                                           'tender' => $tender,
                                           'quantity_and_delivery_times' => $quantity_and_delivery_times,
                                           'existed_qty_ids' => $existed_qty_ids,
                                           'is_rating' => $is_rating]);
        } else {
            Alert::toast('Bạn không quyền chào thầu tender này!', 'error', 'top-right');
            return redirect()->route('user.tenders.index');
        }
    }

    public function destroy($id)
    {
        $bid = Bid::findOrFail($id);
        $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $bid->tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();

        if('Đóng' != $bid->tender->status
            && Carbon::now()->lessThan($bid->tender->tender_end_time)
            && in_array(Auth::user()->id, $users)) {

            //Create User Activity Log
            $activity_log = new UserActivityLog();
            $activity_log->user_id = Auth::user()->id;
            $activity_log->tender_id = $bid->tender_id;
            $activity_log->quantity_id = $bid->quantity_id;
            $activity_log->activity_type = 'Xóa';
            $activity_log->old_price = $bid->price;
            $activity_log->old_price_unit = $bid->price_unit;
            $activity_log->save();

            //Destroy the bid
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
        $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();
        if('Đóng' != $tender->status
            && Carbon::now()->lessThan($tender->tender_end_time)
            && in_array(Auth::user()->id, $users)) {
            $rules = [
                'quantity_id' => 'required',
                'price' => 'required',
                'price_unit' => 'required',
                'bid_quantity' => 'required',
                'bid_quantity_unit' => 'required',
            ];
            $messages = [
                'quantity_id.required' => 'Bạn phải chọn số lượng.',
                'price.required' => 'Bạn phải nhập giá.',
                'price_unit.required' => 'Bạn phải chọn loại tiền tệ.',
                'bid_quantity.required' => 'Bạn phải nhập số lượng chào.',
                'bid_quantity_unit.required' => 'Bạn phải nhập đơn vị chào.',
            ];
            $request->validate($rules,$messages);

            //Check if exits Bid for this QuantityAndDeliveryTime
            $exited_bids = Bid::where('quantity_id', $request->quantity_id)->where('user_id', Auth::user()->id)->get();
            if($exited_bids->count()){
                Alert::toast('Chào giá cho lượng này đã tồn tại! Bạn vui lòng chọn lượng khác', 'error', 'top-right');
                return redirect()->back();
            }

            $bid = new Bid();
            $bid->tender_id = $tender_id;
            $bid->user_id = Auth::user()->id;
            $bid->supplier_id = Auth::user()->supplier->id;
            $bid->quantity_id = $request->quantity_id;
            $bid->price = $request->price;
            $bid->price_unit = $request->price_unit;
            $bid->bid_quantity = $request->bid_quantity;
            $bid->bid_quantity_unit = $request->bid_quantity_unit;
            $bid->pack = $request->pack;
            $bid->delivery_time = $request->delivery_time;
            $bid->note = $request->note;
            $bid->seller = $request->seller;
            $bid->save();

            //Create User Activity Log
            $activity_log = new UserActivityLog();
            $activity_log->user_id = Auth::user()->id;
            $activity_log->tender_id = $bid->tender_id;
            $activity_log->quantity_id = $bid->quantity_id;
            $activity_log->activity_type = 'Thêm';
            $activity_log->new_price = $bid->price;
            $activity_log->new_price_unit = $bid->price_unit;
            $activity_log->save();

            //Send notification to email
            if($tender->is_competitive_bids){
                $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
                $users = User::where('id', '!=', Auth::user()->id)->whereIn('supplier_id', $selected_supplier_ids)->get();
                foreach($users as $user)  {
                    Notification::route('mail' , $user->email)->notify(new BidCreatedOrUpdated($tender->id));
                }
            }

            Alert::toast('Gửi chào giá thành công!', 'success', 'top-right');
            return redirect()->route('user.bids.index', $tender_id);
        } else {
            Alert::toast('Không thể gửi chào giá vì Tender đã hết hạn!', 'error', 'top-right');
            return redirect()->route('user.tenders.index');
        }
    }

    public function anyData()
    {
        $user_id = Auth::user()->id;
        $bids = Bid::orderBy('id', 'desc')->where('user_id', $user_id)->with(['tender', 'quantity'])->get();
        return Datatables::of($bids)
            ->addIndexColumn()
            ->editColumn('titlelink', function ($bids) {
                return '<a href="'.route('user.tenders.show', $bids->tender_id).'">' .$bids->tender->title.'</a>';
            })
            ->editColumn('material', function ($bids) {
                return $bids->quantity->material->name;
            })
            ->editColumn('quantity_and_delivery_id', function ($bids) {
                return number_format($bids->quantity->quantity, 0, '.', ',') . '(' . $bids->quantity->quantity_unit . ')' . ' | ' . $bids->quantity->delivery_time;
            })
            ->editColumn('price', function ($bids) {
                return $bids->price . '(' . $bids->price_unit . ')';
            })
            ->editColumn('origin', function ($bids) {
                return $bids->origin;
            })
            ->editColumn('is_selected', function ($bids) {
                if($bids->tender->status == 'Đóng') {
                    if($bids->is_selected == 1) {
                        return '<span class="badge badge-success">Trúng</span>';

                    } else {
                        return '<span class="badge badge-danger">Trượt</span>';
                    }
                } else {
                    return '<span class="badge badge-warning">Đang xét</span>';
                }
            })
            ->rawColumns(['titlelink', 'is_selected'])
            ->make(true);
    }

    public function edit($id)
    {
        $bid = Bid::findOrFail($id);
        $tender = Tender::findOrFail($bid->tender_id);
        $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();
        if('Đóng' != $tender->status
            && Carbon::now()->lessThan($tender->tender_end_time)
            && in_array(Auth::user()->id, $users)) {
            $quantity_and_delivery_times = QuantityAndDeliveryTime::where('tender_id', $tender->id)->orderBy('id', 'desc')->get();
            return view('user.bid.edit',
                        ['tender' => $tender,
                        'bid' => $bid,
                        'quantity_and_delivery_times' => $quantity_and_delivery_times]);
        }else{
            Alert::toast('Tender đã hết hạn. Không thể sửa!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        $bid = Bid::findOrFail($id);
        $tender = Tender::findOrFail($bid->tender_id);
        $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();

        if('Đóng' != $tender->status
            && Carbon::now()->lessThan($tender->tender_end_time)
            && in_array(Auth::user()->id, $users)) {
            $rules = [
                'quantity_id' => 'required',
                'price' => 'required',
                'price_unit' => 'required',
                'bid_quantity' => 'required',
                'bid_quantity_unit' => 'required',
            ];
            $messages = [
                'quantity_id.required' => 'Bạn phải chọn số lượng.',
                'price.required' => 'Bạn phải nhập giá.',
                'price_unit.required' => 'Bạn phải chọn loại tiền tệ.',
                'bid_quantity.required' => 'Bạn phải nhập số lượng chào.',
                'bid_quantity_unit.required' => 'Bạn phải nhập đơn vị chào.',
            ];
            $request->validate($rules,$messages);

            //Store old price
            $old_price = $bid->price;
            $old_price_unit = $bid->price_unit;

            //Update the bid
            $bid->user_id = Auth::user()->id;
            $bid->supplier_id = Auth::user()->supplier->id;
            $bid->quantity_id = $request->quantity_id;
            $bid->price = $request->price;
            $bid->price_unit = $request->price_unit;
            $bid->bid_quantity = $request->bid_quantity;
            $bid->bid_quantity_unit = $request->bid_quantity_unit;
            $bid->pack = $request->pack;
            $bid->delivery_time = $request->delivery_time;
            $bid->note = $request->note;
            $bid->seller = $request->seller;
            $bid->save();

            //Create User Activity Log
            $activity_log = new UserActivityLog();
            $activity_log->user_id = Auth::user()->id;
            $activity_log->tender_id = $bid->tender_id;
            $activity_log->quantity_id = $bid->quantity_id;
            $activity_log->activity_type = 'Sửa';
            $activity_log->new_price = $bid->price;
            $activity_log->new_price_unit = $bid->price_unit;
            $activity_log->old_price = $old_price;
            $activity_log->old_price_unit = $old_price_unit;
            $activity_log->save();

            //Send notification to email
            if($tender->is_competitive_bids){
                $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
                $users = User::where('id', '!=', Auth::user()->id)->whereIn('supplier_id', $selected_supplier_ids)->get();
                foreach($users as $user)  {
                    Notification::route('mail' , $user->email)->notify(new BidCreatedOrUpdated($tender->id));
                }
            }

            Alert::toast('Cập nhật thông tin thành công!', 'success', 'top-right');
            return redirect()->route('user.bids.index', $tender->id);
        }else{
            Alert::toast('Tender đã hết hạn. Không thể sửa!', 'error', 'top-right');
            return redirect()->back();
        }
    }
}
