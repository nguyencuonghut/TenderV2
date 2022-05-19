<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Bid;
use App\Models\Material;
use App\Models\MaterialSupplier;
use App\Models\QuantityAndDeliveryTime;
use App\Models\Supplier;
use Datatables;
use App\Models\Tender;
use App\Models\TenderPropose;
use App\Models\TenderSuppliersSelectedStatus;
use App\Models\User;
use App\Notifications\ReminderTenderInProgress;
use App\Notifications\TenderCreated;
use App\Notifications\TenderInProgress;
use App\Notifications\TenderResult;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use RealRashid\SweetAlert\Facades\Alert;

class AdminTenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tender.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->can('create-tender')){
            $materials = Material::all()->pluck('name', 'id');
            $creators = Admin::all()->pluck('name', 'id');
            $suppliers = Supplier::all()->pluck('name', 'id');
            return view('admin.tender.create', ['materials' => $materials, 'creators' => $creators, 'suppliers' => $suppliers]);
        }else {
            Alert::toast('Bạn không có quyền tạo tender!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->can('store-tender')){
            $rules = [
                'title' => 'required',
                'material_id' => 'required',
                'delivery_condition' => 'required',
                'payment_condition' => 'required',
                'date_range' => 'required',
            ];
            $messages = [
                'title.required' => 'Bạn phải nhập tiêu đề.',
                'material_id.required' => 'Bạn phải nhập tên hàng.',
                'delivery_condition.required' => 'Bạn phải nhập điều kiện giao hàng.',
                'payment_condition.required' => 'Bạn phải nhập điều kiện thanh toán.',
                'date_range.required' => 'Bạn phải nhập thời gian mở thầu.',
            ];
            $request->validate($rules,$messages);

            //Check the Supplier is existed or not
            $supplier_ids = MaterialSupplier::where('material_id' ,'=' ,$request->material_id)->pluck('supplier_id')->toArray();
            $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();
            if(!$suppliers->count()) {
                Alert::toast('Hàng hóa này chưa có nhà cung cấp! Bạn cần bổ sung nhà cung cấp.', 'error', 'top-right');
                return redirect()->route('admin.tenders.index');
            }

            $last_tender = Tender::latest()->first();
            if($last_tender == null) {
                $tender_id = 1;
            } else {
                $tender_id = $last_tender->id + 1;
            }

            $tender = new Tender();
            $tender->code = 'NL' . '/' . $tender_id . '/' . Carbon::now()->month . '/' . Carbon::now()->year;
            $tender->title = $request->title;
            $tender->material_id = $request->material_id;
            $tender->origin = $request->origin;
            $tender->packing = $request->packing;
            $tender->delivery_condition = $request->delivery_condition;
            $tender->payment_condition = $request->payment_condition;
            $tender->certificate = $request->certificate;
            $tender->other_term = $request->other_term;
            $tender->creator_id = Auth::user()->id;
            $tender->status = 'Open';

            //Parse date range
            $dates = explode(' - ', $request->date_range);
            $tender->tender_start_time = Carbon::parse($dates[0]);
            $tender->tender_end_time = Carbon::parse($dates[1]);
            $tender->save();

            $request->session()->put('tender', $tender);
            return redirect()->route('admin.tenders.createQuantityAndDeliveryTimes');
        }else {
            Alert::toast('Bạn không có quyền tạo tender!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tender = Tender::findOrFail($id);

        //Get the array of Selected Supplier Id
        $supplier_ids = MaterialSupplier::where('material_id' ,'=' ,$tender->material_id)->pluck('supplier_id')->toArray();
        $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();
        $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();

        //Get the array of Supplier Id that send the bid
        $bids = Bid::where('tender_id', $tender->id)->get();
        $bided_supplier_ids = [];
        foreach($bids as $bid) {
            $user = User::findOrFail($bid->user_id);
            array_push($bided_supplier_ids, $user->supplier_id);
        }

        if(Carbon::now()->greaterThan($tender->tender_end_time)
            || $tender->status != 'In-progress') {
            $bids = Bid::with('user')->where('tender_id', $tender->id)->orderBy('quantity_id', 'asc')->get();
            $selected_bids = Bid::where('tender_id', $tender->id)->where('is_selected', true)->get();
            $selected_bided_supplier_ids = [];
            foreach($selected_bids as $bid) {
                $user = User::findOrFail($bid->user_id);
                array_push($selected_bided_supplier_ids, $user->supplier_id);
            }

            $quantity_and_delivery_times = QuantityAndDeliveryTime::where('tender_id', $tender->id)->get();
            $proposes = TenderPropose::where('tender_id', $tender->id)->get();
            $supplier_selected_statuses = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->get();

            $unique_bided_supplier_ids = [];
            foreach($bids as $bid) {
                array_push($unique_bided_supplier_ids, $bid->user->supplier->id);
            }
            return view('admin.tender.show',
                        ['tender' => $tender,
                         'bids' => $bids,
                         'suppliers' => $suppliers,
                         'selected_supplier_ids' => $selected_supplier_ids,
                         'bided_supplier_ids' => $bided_supplier_ids,
                         'selected_bids' => $selected_bids,
                         'quantity_and_delivery_times' => $quantity_and_delivery_times,
                         'proposes' => $proposes,
                         'supplier_selected_statuses' => $supplier_selected_statuses,
                         'unique_bided_supplier_ids' => collect($unique_bided_supplier_ids)->unique(),
                         'selected_bided_supplier_ids' => $selected_bided_supplier_ids
                        ]);
        } else {
            Alert::toast('Tender đang diễn ra. Bạn không quyền xem tender này!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->can('edit-tender')){
            $tender = Tender::findOrFail($id);
            $materials = Material::all()->pluck('name', 'id');
            if($tender->status == 'Open'){
                return view('admin.tender.edit', ['tender' => $tender,
                                                  'materials' => $materials
                                                 ]);
            }else{
                Alert::toast('Tender đang diễn ra hoặc đã đóng, không thể sửa!', 'error', 'top-right');
                return redirect()->route('admin.tenders.index');
            }
        }else{
            Alert::toast('Bạn không có quyền sửa tender này!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Auth::user()->can('update-tender')){
            $rules = [
                'title' => 'required',
                'material_id' => 'required',
                'delivery_condition' => 'required',
                'payment_condition' => 'required',
                'date_range' => 'required',
            ];
            $messages = [
                'title.required' => 'Bạn phải nhập tiêu đề.',
                'material_id.required' => 'Bạn phải nhập tên hàng.',
                'delivery_condition.required' => 'Bạn phải nhập điều kiện giao hàng.',
                'payment_condition.required' => 'Bạn phải nhập điều kiện thanh toán.',
                'date_range.required' => 'Bạn phải nhập thời gian mở thầu.',
            ];
            $request->validate($rules,$messages);

            //Check the Supplier is existed or not
            $supplier_ids = MaterialSupplier::where('material_id' ,'=' ,$request->material_id)->pluck('supplier_id')->toArray();
            $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();
            if(!$suppliers->count()) {
                Alert::toast('Hàng hóa này chưa có nhà cung cấp! Bạn cần bổ sung nhà cung cấp.', 'error', 'top-right');
                return redirect()->route('admin.tenders.index');
            }

            $tender = Tender::findOrFail($id);
            $tender->title = $request->title;
            $tender->material_id = $request->material_id;
            $tender->origin = $request->origin;
            $tender->packing = $request->packing;
            $tender->delivery_condition = $request->delivery_condition;
            $tender->payment_condition = $request->payment_condition;
            $tender->certificate = $request->certificate;
            $tender->other_term = $request->other_term;
            $tender->creator_id = Auth::user()->id;
            $tender->status = 'Open';

            //Parse date range
            $dates = explode(' - ', $request->date_range);
            $tender->tender_start_time = Carbon::parse($dates[0]);
            $tender->tender_end_time = Carbon::parse($dates[1]);
            $tender->save();

            $request->session()->put('tender', $tender);
            return redirect()->route('admin.tenders.editQuantityAndDeliveryTimes');
        }else {
            Alert::toast('Bạn không có quyền tạo tender!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->can('destroy-tender')){
            $tender = Tender::findOrFail($id);
            if('Open' == $tender->status) {
                $tender->destroy($id);
                Alert::toast('Xóa tender thành công!', 'success', 'top-right');
            } else {
                Alert::toast('Tender đang diễn ra. Không thể xóa!', 'error', 'top-right');
            }
            return redirect()->route('admin.tenders.index');
        }else {
            Alert::toast('Bạn không có quyền xóa tender!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
    }

    public function anyData()
    {
        $tenders = Tender::with('creator')->with('material')->orderBy('id', 'desc')->select(['id', 'title', 'material_id', 'tender_start_time', 'tender_end_time', 'creator_id', 'status'])->get();
        return Datatables::of($tenders)
            ->addIndexColumn()
            ->editColumn('titlelink', function ($tenders) {
                return '<a href="'.route('admin.tenders.show', $tenders->id).'">'.$tenders->title.'</a>';
            })
            ->editColumn('material_id', function ($tenders) {
                return $tenders->material->name;
            })
            ->editColumn('status', function ($tenders) {
                if($tenders->status == 'Open') {
                    return '<span class="badge badge-primary">Open</span>';

                } else if($tenders->status == 'Closed'){
                    return '<span class="badge badge-success">Closed</span>';
                } else {
                    return '<span class="badge badge-warning">In-progress</span>';
                }
            })
            ->editColumn('tender_start_time', function ($tenders) {
                return date('d/m/Y H:i', strtotime($tenders->tender_start_time));
            })
            ->editColumn('tender_end_time', function ($tenders) {
                return date('d/m/Y H:i', strtotime($tenders->tender_end_time));
            })
            ->addColumn('change_status', function ($tenders) {
                return '<a href="' . route("admin.tenders.changeStatus", $tenders->id) . '" class="btn btn-primary"><i class="fas fa-random"></i></a>';
            })
            ->addColumn('edit', function ($tenders) {
                return '<a href="' . route("admin.tenders.edit", $tenders->id) . '" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>';
            })
            ->addColumn('delete', '
                <form action="{{ route(\'admin.tenders.destroy\', $id) }}" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" class="btn btn-danger" onClick="return confirm(\'Bạn có chắc chắn muốn xóa?\')"">
                    <i class="fas fa-trash-alt"></i>
                    </button>

                    {{csrf_field()}}
                </form>')
            ->rawColumns(['titlelink', 'status', 'edit', 'delete', 'change_status'])
            ->make(true);
    }

    public function changeStatus($id)
    {
        if(Auth::user()->can('change-status')){
            $tender = Tender::findOrFail($id);
            $supplier_selected_statuses = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->get();
            $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();

            //Get the array of Supplier Id that send the bid and selected
            $selected_bids = Bid::where('tender_id', $tender->id)->where('is_selected', true)->get();
            $selected_bided_supplier_ids = [];
            foreach($selected_bids as $bid) {
                $user = User::findOrFail($bid->user_id);
                array_push($selected_bided_supplier_ids, $user->supplier_id);
            }

            //Get the array of Supplier Id that send the bid
            $bided_supplier_ids = [];
            $bids = Bid::where('tender_id', $tender->id)->get();
            foreach($bids as $bid) {
                $user = User::findOrFail($bid->user_id);
                array_push($bided_supplier_ids, $user->supplier_id);
            }
            return view('admin.tender.changestatus',
                        ['tender' => $tender,
                        'supplier_selected_statuses' => $supplier_selected_statuses,
                        'selected_supplier_ids' => $selected_supplier_ids,
                        'bided_supplier_ids' => $bided_supplier_ids,
                        'selected_bided_supplier_ids' => $selected_bided_supplier_ids,
                        ]);
        }else {
            Alert::toast('Bạn không có quyền chuyển trạng thái tender!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if(Auth::user()->can('change-status')){
            $rules = [
                'status' => 'required',
                'supplier_ids' => 'required',
            ];
            $messages = [
                'status.required' => 'Bạn phải chọn trạng thái.',
                'supplier_ids.required' => 'Bạn phải chọn nhà thầu',
            ];
            $request->validate($rules,$messages);

            $tender = Tender::findOrFail($id);
            //Check status is updated or not
            if($request->status == $tender->status) {
                Alert::toast('Trạng thái không có gì thay đổi. Bạn vui lòng kiểm tra lại!', 'warning', 'top-right');
                return redirect()->back();
            }
            $tender->status = $request->status;
            $tender->approver_id = Auth::user()->id;

            //Send notification email to suppliers
            if('In-progress' == $request->status) {
                //Get the mail list
                $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
                $users = User::whereIn('supplier_id', $selected_supplier_ids)->get();
                foreach($users as $user)  {
                    //Notify now
                    Notification::route('mail' , $user->email)->notify(new TenderInProgress($tender->id));
                    //Send reminder to customers 15 minutes before the Tender starts
                    $start_time = Carbon::parse($tender->tender_start_time);
                    $delay = $start_time->addMinutes(-15);
                    Notification::route('mail' , $user->email)->notify((new ReminderTenderInProgress($tender->id))->delay($delay));
                }

                //Update the in-progress time
                $tender->tender_in_progress_time = Carbon::now();
                //Save the tender
                $tender->save();

                Alert::toast('Tender chuyển sang trạng thái Hoạt Động. Bắt đầu đấu thầu!', 'success', 'top-right');
                return redirect()->route('admin.tenders.index');
            } else if('Closed' == $request->status) {
                //Check if bids are selected or not
                $bids = Bid::where('tender_id', $tender->id)->where('is_selected', 1)->get();
                if($bids->count() == 0){
                    Alert::toast('Chưa có nhà cung cấp nào được chọn. Không cho phép chuyển sang trạng thái Đóng!', 'error', 'top-right');
                    return redirect()->route('admin.tenders.index');
                }else{
                    //Get the mail list
                    $bided_user_ids = [];
                    $bided_user_ids = Bid::where('tender_id', $tender->id)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $bided_user_ids)->get();
                    foreach($users as $user)  {
                        Notification::route('mail' , $user->email)->notify(new TenderResult($tender->id));
                    }

                    //Update the closed time
                    $tender->tender_closed_time = Carbon::now();
                    //Save the tender
                    $tender->save();

                    Alert::toast('Tender chuyển sang trạng thái Đóng. Kết thúc đấu thầu!', 'success', 'top-right');
                    return redirect()->route('admin.tenders.index');
                }
            }else{
                Alert::toast('Không cho phép chuyển về trạng thái Open!', 'error', 'top-right');
                return redirect()->route('admin.tenders.index');
            }
        } else {
            Alert::toast('Bạn không có quyền chuyển trạng thái tender!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }

    }

    public function createQuantityAndDeliveryTimes(Request $request)
    {
        $tender = $request->session()->get('tender');
        return view('admin.tender.create_quantity_and_delivery_times', ['tender' => $tender]);
    }

    public function storeQuantityAndDeliveryTimes(Request $request)
    {
        $request->validate([
            'addmore.*.quantity' => 'required',
            'addmore.*.quantity_unit' => 'required',
            'addmore.*.delivery_time' => 'required',
        ]);

        $tender = $request->session()->get('tender');

        foreach ($request->addmore as $key => $value) {
            QuantityAndDeliveryTime::create($value);
        }

        $request->session()->put('tender', $tender);
        return redirect()->route('admin.tenders.createSuppliers');
    }


    public function editQuantityAndDeliveryTimes(Request $request)
    {
        $tender = $request->session()->get('tender');
        $quantity_and_delivery_times = QuantityAndDeliveryTime::where('tender_id', $tender->id)->get();
        return view('admin.tender.edit_quantity_and_delivery_times',
                     ['tender' => $tender,
                    'quantity_and_delivery_times' => $quantity_and_delivery_times
                     ]);
    }

    public function updateQuantityAndDeliveryTimes(Request $request)
    {
        $request->validate([
            'addmore.*.quantity' => 'required',
            'addmore.*.quantity_unit' => 'required',
            'addmore.*.delivery_time' => 'required',
        ]);

        $tender = $request->session()->get('tender');

        //Delete all quantity and delivery times before
        $old_quantity_and_delivery_times = QuantityAndDeliveryTime::where('tender_id', $tender->id)->get();
        foreach($old_quantity_and_delivery_times as $item) {
            $item->destroy($item->id);
        }

        //Update new quantity and delivery times
        foreach ($request->addmore as $key => $value) {
            QuantityAndDeliveryTime::create($value);
        }

        $request->session()->put('tender', $tender);
        return redirect()->route('admin.tenders.createSuppliers');
    }

    public function createSuppliers(Request $request)
    {
        $tender = $request->session()->get('tender');
        $supplier_ids = MaterialSupplier::where('material_id' ,'=' ,$tender->material_id)->pluck('supplier_id')->toArray();
        $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();

        if(!$suppliers->count()) {
            Alert::toast('Hàng hóa này chưa có nhà cung cấp! Bạn cần bổ sung nhà cung cấp.', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        } else {
            return view('admin.tender.createsuppliers', ['suppliers' => $suppliers, 'tender' => $tender]);
        }
    }

    public function storeSuppliers(Request $request)
    {
        //dd($request->all());
        $tender = $request->session()->get('tender');

        //Store the selected status of suppliers
        $supplier_ids = MaterialSupplier::where('material_id' ,'=' ,$tender->material_id)->pluck('supplier_id')->toArray();
        $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();
        //dd($suppliers);
        foreach($suppliers as $key => $value){
            $supplier_selected_status = new TenderSuppliersSelectedStatus;
            $supplier_selected_status->tender_id = $tender->id;
            $supplier_selected_status->supplier_id = $value->id;
            //dd(in_array($value->id, $request->supplier_ids));
            $supplier_selected_status->is_selected = in_array($value->id, $request->supplier_ids);// ? 1:0;//isset($request->supplier_ids[$key]);
            $supplier_selected_status->reason = $request->reasons[$key];
            $supplier_selected_status->save();
        }

        //Send email notification
        $tender->notify(new TenderCreated($tender->id));

        Alert::toast('Tạo tender thành công!', 'success', 'top-right');
        return redirect()->route('admin.tenders.index');
    }


    public function createResult($id)
    {
        if(Auth::user()->can('create-result')){
            $tender = Tender::findOrFail($id);
            if(Carbon::now()->lessThan($tender->tender_end_time)){
                Alert::toast('Thời gian chào thầu chưa kết thúc. Bạn không có chọn kết quả thầu!', 'error', 'top-right');
                return redirect()->route('admin.tenders.index');
            }else{
                //Get the array of Supplier Id that send the bid
                $bids = Bid::where('tender_id', $tender->id)->orderBy('quantity_id', 'asc')->get();
                $bided_supplier_ids = [];
                foreach($bids as $bid) {
                    $user = User::findOrFail($bid->user_id);
                    array_push($bided_supplier_ids, $user->supplier_id);
                }
                $suppliers = Supplier::whereIn('id', $bided_supplier_ids)->orderBy('id', 'asc')->get();

                $selected_bids = Bid::where('tender_id', $tender->id)->where('is_selected', true)->get();
                return view('admin.tender.result', ['tender' => $tender, 'suppliers' => $suppliers, 'bids' => $bids, 'selected_bids' => $selected_bids]);
            }
        }else{
            Alert::toast('Bạn không có quyền đề xuất kết quả thầu!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function storeResult(Request $request, $id)
    {
        if(Auth::user()->can('store-result')){
            $rules = [
                'tender_quantity' => 'required',
                'tender_quantity_unit' => 'required',
                'bid_id' => 'required',
            ];
            $messages = [
                'tender_quantity.required' => 'Bạn phải nhập số lượng.',
                'tender_quantity_unit.required' => 'Bạn phải chọn đơn vị.',
                'bid_id.required' => 'Bạn phải chọn giá.',
            ];
            $request->validate($rules,$messages);

            //Update bid
            $bid = Bid::findOrFail($request->bid_id);
            $bid->tender_quantity = $request->tender_quantity;
            $bid->tender_quantity_unit = $request->tender_quantity_unit;
            $bid->is_selected = true;
            $bid->save();

            Alert::toast('Đề xuất kết quả thầu thành công!', 'success', 'top-right');
            return redirect()->route('admin.tenders.createResult', $id);
        }else{
            Alert::toast('Bạn không có quyền đề xuất kết quả thầu!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function destroyResult($bid_id)
    {
        if(Auth::user()->can('destroy-result')){
            //Update bid
            $bid = Bid::findOrFail($bid_id);
            $bid->tender_quantity = 0;
            $bid->tender_quantity_unit = 'tấn';
            $bid->is_selected = false;
            $bid->save();

            $tender = Tender::findOrFail($bid->tender_id);

            Alert::toast('Xóa đề xuất thầu thành công!', 'success', 'top-right');
            return redirect()->route('admin.tenders.createResult', $tender->id);
        }else{
            Alert::toast('Bạn không có quyền xóa kết quả thầu!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function createPropose(Request $request, $id)
    {
        if(Auth::user()->can('update-tender')){
            $rules = [
                'propose' => 'required',
            ];
            $messages = [
                'propose.required' => 'Bạn phải nhập nội dung đề xuất.',
            ];
            $request->validate($rules,$messages);

            //Create new Propose
            $propose = new TenderPropose();
            $propose->tender_id = $id;
            $propose->propose = $request->propose;
            $propose->save();

            Alert::toast('Tạo đề xuất thành công!', 'success', 'top-right');
            return redirect()->back();
        }else{
            Alert::toast('Bạn không có quyền tạo đề xuất cho Tender này!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function destroyPropose($id)
    {
        if(Auth::user()->can('destroy-propose')){
            $propose = TenderPropose::findOrFail($id);
            $tender = Tender::findOrFail($propose->tender_id);
            if('Closed' != $tender->status) {
                $propose->destroy($id);
                Alert::toast('Xóa đề xuất thành công!', 'success', 'top-right');
            } else {
                Alert::toast('Tender đã đóng. Không thể xóa đề xuất!', 'error', 'top-right');
            }
            return redirect()->back();
        }else {
            Alert::toast('Bạn không có quyền xóa đề xuất này!', 'error', 'top-right');

        }
    }
}
