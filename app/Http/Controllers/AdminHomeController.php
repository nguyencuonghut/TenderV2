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
        $all_tenders_count = Tender::count();
        $completed_tenders_count = Tender::where('status', 'Closed')->count();
        $in_progress_tenders_count= Tender::where('status', 'In-progress')->count();
        $suppliers_count = Supplier::count();

        return view('admin.home', ['all_tenders_count' => $all_tenders_count,
                                    'completed_tenders_count' =>$completed_tenders_count,
                                    'in_progress_tenders_count' =>$in_progress_tenders_count,
                                    'suppliers_count' => $suppliers_count]);
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
