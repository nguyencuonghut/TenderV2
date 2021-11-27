<?php

namespace App\Http\Controllers;

use App\Models\Tender;
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
        $selected_supplier_ids = [];
        $selected_supplier_ids = explode(",", $tender->supplier_ids);
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('id')->toArray();
        if('Open' !=  $tender->status
            && in_array(Auth::user()->id, $users)) {
            return view('user.tender.show', ['tender' => $tender]);
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
        $tenders = Tender::with('material')->orderBy('id', 'desc')->select(['id', 'title', 'material_id', 'tender_start_time', 'tender_end_time', 'status', 'supplier_ids'])->get();
        foreach($tenders as $tender) {
            $selected_supplier_ids = [];
            $selected_supplier_ids = explode(",", $tender->supplier_ids);
            if(in_array(Auth::user()->supplier_id, $selected_supplier_ids)) {
                $user_tenders->push($tender);
            }
        }
        return Datatables::of($user_tenders)
            ->addIndexColumn()
            ->editColumn('title', function ($user_tenders) {
                return $user_tenders->title;
            })
            ->editColumn('material_id', function ($user_tenders) {
                return $user_tenders->material->name;
            })
            ->editColumn('tender_start_time', function ($user_tenders) {
                return $user_tenders->tender_start_time;
            })
            ->editColumn('tender_end_time', function ($user_tenders) {
                return $user_tenders->tender_end_time;
            })
            ->addColumn('show', function ($user_tenders) {
                return '<a href="' . route("user.tenders.show", $user_tenders->id) . '" class="btn btn-primary"><i class="fas fa-eye"></i></a>';
            })
            ->addColumn('bid', function ($user_tenders) {
                return '<a href="' . route("user.tenders.bid", $user_tenders->id) . '" class="btn btn-success"><i class="fas fa-gavel"></i></a>';
            })
            ->rawColumns(['show', 'bid'])
            ->make(true);
    }

    public function bid($id)
    {
        return "Bỏ thầu";
    }
}
