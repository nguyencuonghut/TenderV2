<?php

namespace App\Http\Controllers;

use App\Models\UserActivityLog;
use Datatables;

class AdminUserActivityLogController extends Controller
{
    public function anyData($tender_id)
    {
        $logs = UserActivityLog::where('tender_id', $tender_id)->with('user')->get();
        return Datatables::of($logs)
            ->editColumn('supplier', function ($logs) {
                return $logs->user->supplier->name;
            })
            ->editColumn('quantity', function ($logs) {
                return $logs->quantity->quantity . ' (' . $logs->quantity->quantity_unit . ')' . ' - ' . $logs->quantity->delivery_time;
            })
            ->editColumn('activity_type', function ($logs) {
                if($logs->activity_type == 'Thêm') {
                    return '<span class="badge badge-success">Thêm</span>';
                } else if($logs->activity_type == 'Sửa'){
                    return '<span class="badge badge-warning">Sửa</span>';
                } else if($logs->activity_type == 'Xóa'){
                    return '<span class="badge badge-danger">Xóa</span>';
                }
            })
            ->editColumn('new_price', function ($logs) {
                if($logs->new_price) {
                    return $logs->new_price . '(' . $logs->new_price_unit . ')';
                } else{
                    return '-';
                }
            })
            ->editColumn('old_price', function ($logs) {
                if($logs->old_price ) {
                    return $logs->old_price . '(' . $logs->old_price_unit . ')';
                } else{
                    return '-';
                }
            })
            ->editColumn('created_at', function ($logs) {
                return $logs->created_at;
            })
            ->rawColumns(['activity_type'])
            ->make(true);
    }
}
