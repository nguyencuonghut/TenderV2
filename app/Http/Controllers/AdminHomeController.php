<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Tender;
use Database\Seeders\SuppliersTableSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminHomeController extends Controller
{
    public function index()
    {
        $all_tenders = Tender::orderBy('id', 'desc')->get();
        $completed_tenders = Tender::where('status', 'Closed')->orderBy('id', 'desc')->get();
        $in_progress_tenders = Tender::where('status', 'In-progress')->orderBy('id', 'desc')->get();
        $suppliers = Supplier::orderBy('id', 'desc')->get();

        return view('admin.home', ['all_tenders' => $all_tenders,
                                    'completed_tenders' =>$completed_tenders,
                                    'in_progress_tenders' =>$in_progress_tenders,
                                    'suppliers' => $suppliers]);
    }

    public function profile()
    {
        $recent_tenders = Tender::where('creator_id', Auth::user()->id)->orWhere('approver_id', Auth::user()->id)->orderBy('id', 'desc')->take(3)->get();
        $my_tenders = Tender::where('creator_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $my_tenders_cnt = $my_tenders->count();
        $my_approved_tenders = Tender::where('approver_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $my_approved_tenders_cnt = $my_approved_tenders->count();
        return view('admin.profile', ['recent_tenders' => $recent_tenders,
                                      'my_tenders_cnt' => $my_tenders_cnt,
                                      'my_approved_tenders_cnt' => $my_approved_tenders_cnt
                                    ]);
    }
}
