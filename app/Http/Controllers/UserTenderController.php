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
        if('Open' !=  $tender->status
            && in_array(Auth::user()->id, $users)) {

            $bids = Bid::where('tender_id', $tender->id)->where('user_id', Auth::user()->id)->get();
            $selected_bids = Bid::where('tender_id', $tender->id)->where('is_selected', true)->get();
            $quantity_and_delivery_times = QuantityAndDeliveryTime::where('tender_id', $tender->id)->orderBy('id', 'desc')->get();
            $existed_qty_ids = Bid::where('tender_id', $tender->id)->pluck('quantity_id')->toArray();

            return view('user.tender.show', ['tender' => $tender,
                                             'bids' => $bids,
                                             'selected_bids' => $selected_bids,
                                             'quantity_and_delivery_times' => $quantity_and_delivery_times,
                                             'existed_qty_ids' => $existed_qty_ids]);
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
        $tenders = Tender::with('material')->where('status', '<>', 'Open')->orderBy('id', 'desc')->select(['id', 'title', 'material_id', 'tender_start_time', 'tender_end_time', 'status'])->get();
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
            ->editColumn('material_id', function ($user_tenders) {
                return $user_tenders->material->name;
            })
            ->editColumn('tender_start_time', function ($user_tenders) {
                return date('d/m/Y H:i', strtotime($user_tenders->tender_start_time));
            })
            ->editColumn('tender_end_time', function ($user_tenders) {
                return date('d/m/Y H:i', strtotime($user_tenders->tender_end_time));
            })
            ->editColumn('status', function ($user_tenders) {
                if($user_tenders->status == 'Open') {
                    return '<span class="badge badge-primary">Open</span>';
                } else if($user_tenders->status == 'Closed'){
                    return '<span class="badge badge-success">Closed</span>';
                } else {
                    return '<span class="badge badge-warning">In-progress</span>';
                }
            })
            ->rawColumns(['title', 'status'])
            ->make(true);
    }
}
