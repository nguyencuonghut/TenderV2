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
}
