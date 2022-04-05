<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserHomeController extends Controller
{
    public function index()
    {
        $tenders =  Tender::where('status', '<>', 'Open')->get();
        $my_all_tenders = $tenders->filter(function($item, $key) {
            $selected_supplier_ids = [];
            $selected_supplier_ids = explode(",", $item->supplier_ids);
            $my_supplier_id = Auth::user()->supplier_id;
            if(in_array($my_supplier_id, $selected_supplier_ids)) {
                return $item;
            }
        });

        $closed_tenders =  Tender::where('status', 'Closed')->get();
        $my_completed_tenders = $closed_tenders->filter(function($item, $key) {
            $selected_supplier_ids = [];
            $selected_supplier_ids = explode(",", $item->supplier_ids);
            $my_supplier_id = Auth::user()->supplier_id;
            if(in_array($my_supplier_id, $selected_supplier_ids)) {
                return $item;
            }
        });

        $in_progress_tenders =  Tender::where('status', 'In-progress')->get();
        $my_in_progress_tenders = $in_progress_tenders->filter(function($item, $key) {
            $selected_supplier_ids = [];
            $selected_supplier_ids = explode(",", $item->supplier_ids);
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
}
