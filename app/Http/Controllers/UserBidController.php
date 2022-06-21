<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\QuantityAndDeliveryTime;
use App\Models\Tender;
use App\Models\TenderSuppliersSelectedStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Datatables;

class UserBidController extends Controller
{
    public function index($tender_id)
    {
        $tender = Tender::findOrFail($tender_id);

        $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();
        $existed_qty_ids = Bid::where('tender_id', $tender->id)->pluck('quantity_id')->toArray();
        if(in_array(Auth::user()->id, $users)) {
            $bids = Bid::where('tender_id', $tender_id)->where('user_id', Auth::user()->id)->get();
            $quantity_and_delivery_times = QuantityAndDeliveryTime::where('tender_id', $tender->id)->get();
            return view('user.bid.index', ['bids' => $bids,
                                           'tender' => $tender,
                                           'quantity_and_delivery_times' => $quantity_and_delivery_times,
                                           'existed_qty_ids' => $existed_qty_ids]);
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
        $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();
        if('Closed' != $tender->status
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
            $exited_bids = Bid::where('quantity_id', $request->quantity_id)->get();
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
            $bid->origin = $request->origin;
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

    public function anyData()
    {
        $user_id = Auth::user()->id;
        $bids = Bid::orderBy('id', 'desc')->where('user_id', $user_id)->with(['tender', 'quantity'])->get();
        return Datatables::of($bids)
            ->addIndexColumn()
            ->editColumn('titlelink', function ($bids) {
                return '<a href="'.route('user.tenders.show', $bids->tender_id).'">' . '('. $bids->tender->code . ') ' .$bids->tender->title.'</a>';
            })
            ->editColumn('material_id', function ($bids) {
                return $bids->tender->material->name;
            })
            ->editColumn('quantity_and_delivery_id', function ($bids) {
                return $bids->quantity->quantity . '(' . $bids->quantity->quantity_unit . ')' . ' | ' . $bids->quantity->delivery_time;
            })
            ->editColumn('price', function ($bids) {
                return $bids->price . '(' . $bids->price_unit . ')';
            })
            ->editColumn('origin', function ($bids) {
                return $bids->origin;
            })
            ->editColumn('is_selected', function ($bids) {
                if($bids->tender->status == 'Closed') {
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
        if('Closed' != $tender->status
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

        if('Closed' != $tender->status
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
            $bid->user_id = Auth::user()->id;
            $bid->supplier_id = Auth::user()->supplier->id;
            $bid->quantity_id = $request->quantity_id;
            $bid->price = $request->price;
            $bid->price_unit = $request->price_unit;
            $bid->bid_quantity = $request->bid_quantity;
            $bid->bid_quantity_unit = $request->bid_quantity_unit;
            $bid->pack = $request->pack;
            $bid->origin = $request->origin;
            $bid->delivery_time = $request->delivery_time;
            $bid->delivery_place = $request->delivery_place;
            $bid->payment_condition = $request->payment_condition;
            $bid->note = $request->note;
            $bid->save();
                Alert::toast('Cập nhật thông tin thành công!', 'success', 'top-right');
                return redirect()->route('user.bids.index', $tender->id);
        }else{
            Alert::toast('Tender đã hết hạn. Không thể sửa!', 'error', 'top-right');
            return redirect()->back();
        }
    }
}
