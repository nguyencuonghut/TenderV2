<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\QuantityAndDeliveryTime;
use App\Models\Tender;
use App\Models\TenderSuppliersSelectedStatus;
use App\Models\User;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class UserTenderController extends Controller
{
    public function show($id)
    {
        $tender = Tender::findOrFail($id);
        $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();
        if( in_array(Auth::user()->id, $users)) {
            //Check condition to show the tender
            if('Mở' == $tender->status && false == $tender->is_checked){
                Alert::toast('Tender chưa được duyệt!', 'error', 'top-right');
                return redirect()->route('user.tenders.index');
            }
            $bids = Bid::where('tender_id', $tender->id)->where('user_id', Auth::user()->id)->get();
            $selected_bids = Bid::where('tender_id', $tender->id)->where('is_selected', true)->get();
            $quantity_and_delivery_times = QuantityAndDeliveryTime::where('tender_id', $tender->id)->orderBy('id', 'desc')->get();
            $existed_qty_ids = Bid::where('tender_id', $tender->id)->where('user_id', Auth::user()->id)->pluck('quantity_id')->toArray();
            $price_unit_arr = Bid::where('tender_id', $tender->id)->pluck('price_unit')->toArray();
            $unique = array_count_values($price_unit_arr);
            if($tender->is_competitive_bids
                && 1 == sizeof($unique)){
                $is_rating = true;
            }else{
                $is_rating = false;
            }

            return view('user.tender.show', ['tender' => $tender,
                                             'bids' => $bids,
                                             'selected_bids' => $selected_bids,
                                             'quantity_and_delivery_times' => $quantity_and_delivery_times,
                                             'existed_qty_ids' => $existed_qty_ids,
                                             'is_rating' => $is_rating]);
        } else {
            Alert::toast('Bạn không quyền xem tender này!', 'error', 'top-right');
            return redirect()->route('user.tenders.index');
        }
    }

    public function index()
    {
        return view('user.tender.index');
    }


    public function anyData()
    {
        $user_tenders = collect();
        $tenders = Tender::where('is_checked', true)->orderBy('id', 'desc')->select(['id', 'title', 'tender_in_progress_time', 'tender_end_time', 'status', 'close_reason'])->get();
        foreach($tenders as $tender) {
            $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
            if(in_array(Auth::user()->supplier_id, $selected_supplier_ids)) {
                $user_tenders->push($tender);
            }
        }
        return Datatables::of($user_tenders)
            ->addIndexColumn()
            ->editColumn('title', function ($user_tenders) {
                return '<a href="'.route('user.tenders.show', $user_tenders->id).'">'.$user_tenders->title.'</a>';
            })
            ->editColumn('tender_time_range', function ($user_tenders) {
                return date('d/m/Y H:i', strtotime($user_tenders->tender_in_progress_time)) . ' - ' . date('d/m/Y H:i', strtotime($user_tenders->tender_end_time));
            })
            ->editColumn('status', function ($user_tenders) {
                if($user_tenders->status == 'Mở') {
                    return '<span class="badge badge-primary">Mở</span>';
                } else if($user_tenders->status == 'Đóng'
                    && NULL == $user_tenders->close_reason){
                    return '<span class="badge badge-success">Đóng</span>';
                } else if($user_tenders->status == 'Đóng'
                        && NULL != $user_tenders->close_reason){
                    return '<span class="badge badge-secondary">Đóng</span>';
                } else if($user_tenders->status == 'Đang kiểm tra'){
                    return '<span class="badge badge-info">Đang kiểm tra</span>';
                } else {
                    return '<span class="badge badge-warning">Đang diễn ra</span>';
                }
            })
            ->editColumn('close_reason', function ($user_tenders) {
                return $user_tenders->close_reason;
            })
            ->rawColumns(['title', 'status'])
            ->make(true);
    }
}
