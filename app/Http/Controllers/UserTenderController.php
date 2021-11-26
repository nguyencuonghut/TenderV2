<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTenderController extends Controller
{
    public function show($id)
    {
        $tender = Tender::findOrFail($id);
        $selected_supplier_ids = [];
        $selected_supplier_ids = explode(",", $tender->supplier_ids);
        $users = User::whereIn('supplier_id', $selected_supplier_ids)->pluck('supplier_id')->toArray();
        if('Open' !=  $tender->status
            && in_array(Auth::user()->id, $users)) {
            return Auth::user()->name . " Có quyền xem tender này";
        } else {
            return 'Oop! ' . Auth::user()->name . " Không quyền xem tender này";
        }
    }
}
