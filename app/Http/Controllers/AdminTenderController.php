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
use App\Models\TenderApproveComment;
use App\Models\TenderPropose;
use App\Models\TenderSuppliersSelectedStatus;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Notifications\ReminderTenderInProgress;
use App\Notifications\TenderApproved;
use App\Notifications\TenderCanceled;
use App\Notifications\TenderCreated;
use App\Notifications\TenderInProgress;
use App\Notifications\TenderRequestApprove;
use App\Notifications\TenderRequestAudit;
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
            $creators = Admin::all()->pluck('name', 'id');
            $suppliers = Supplier::all()->pluck('name', 'id');
            return view('admin.tender.create', ['creators' => $creators, 'suppliers' => $suppliers]);
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
                'delivery_condition' => 'required',
                'payment_condition' => 'required',
                'tender_end_time' => 'required',
            ];
            $messages = [
                'title.required' => 'Bạn phải nhập tiêu đề.',
                'delivery_condition.required' => 'Bạn phải nhập điều kiện giao hàng.',
                'payment_condition.required' => 'Bạn phải nhập điều kiện thanh toán.',
                'tender_end_time.required' => 'Bạn phải nhập thời gian đóng thầu.',
            ];
            $request->validate($rules,$messages);

            //Validate the Tender end time
            $tender_end_time = Carbon::parse($request->tender_end_time);
            if(Carbon::now()->addDays(10)->lessThan($tender_end_time)){
                //Not allow to set the tender end time > 10 days from now
                Alert::toast('Thời gian đóng tender không quá 10 ngày từ bây giờ. Bạn vui lòng chọn lại!', 'error', 'top-right');
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
            $tender->origin = $request->origin;
            $tender->packing = $request->packing;
            $tender->delivery_condition = $request->delivery_condition;
            $tender->payment_condition = $request->payment_condition;
            $tender->certificate = $request->certificate;
            $tender->other_term = $request->other_term;
            $tender->freight_charge = $request->freight_charge;
            $tender->creator_id = Auth::user()->id;
            $tender->status = 'Mở';
            $tender->tender_end_time = Carbon::parse($request->tender_end_time);
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
        $material_ids = QuantityAndDeliveryTime::where('tender_id', $tender->id)->pluck('material_id')->toArray();
        $supplier_ids = MaterialSupplier::whereIn('material_id', $material_ids)->pluck('supplier_id')->toArray();
        $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();

        //Get the array of Selected Supplier Id
        $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();

        //Get the array of Supplier Id that send the bid
        $bids = Bid::where('tender_id', $tender->id)->get();
        $bided_supplier_ids = [];
        foreach($bids as $bid) {
            $user = User::withTrashed()->findOrFail($bid->user_id);
            array_push($bided_supplier_ids, $user->supplier_id);
        }

        if(Carbon::now()->greaterThan($tender->tender_end_time)
            || $tender->status != 'Đang diễn ra') {
            $bids = Bid::with('user')->where('tender_id', $tender->id)->orderBy('quantity_id', 'asc')->get();
            $selected_bids = Bid::where('tender_id', $tender->id)->where('is_selected', true)->get();
            $selected_bided_supplier_ids = [];
            foreach($selected_bids as $bid) {
                $user = User::withTrashed()->findOrFail($bid->user_id);
                array_push($selected_bided_supplier_ids, $user->supplier_id);
            }

            $quantity_and_delivery_times = QuantityAndDeliveryTime::where('tender_id', $tender->id)->get();
            $propose = $tender->propose;
            $supplier_selected_statuses = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->get();

            $unique_bided_supplier_ids = [];
            foreach($bids as $bid) {
                array_push($unique_bided_supplier_ids, $bid->user->supplier->id);
            }

            $activity_logs = UserActivityLog::where('tender_id', $tender->id)->orderBy('id', 'asc')->get();
            return view('admin.tender.show',
                        ['tender' => $tender,
                         'bids' => $bids,
                         'suppliers' => $suppliers,
                         'selected_supplier_ids' => $selected_supplier_ids,
                         'bided_supplier_ids' => $bided_supplier_ids,
                         'selected_bids' => $selected_bids,
                         'quantity_and_delivery_times' => $quantity_and_delivery_times,
                         'propose' => $propose,
                         'supplier_selected_statuses' => $supplier_selected_statuses,
                         'unique_bided_supplier_ids' => collect($unique_bided_supplier_ids)->unique(),
                         'selected_bided_supplier_ids' => $selected_bided_supplier_ids,
                         'activity_logs' => $activity_logs
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
            if($tender->status == 'Mở'){
                return view('admin.tender.edit', ['tender' => $tender,
                                                  'materials' => $materials
                                                 ]);
            }else{
                Alert::toast('Tender không ở trạng thái Mở nên không thể sửa!', 'error', 'top-right');
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
                'delivery_condition' => 'required',
                'payment_condition' => 'required',
                'tender_end_time' => 'required',
            ];
            $messages = [
                'title.required' => 'Bạn phải nhập tiêu đề.',
                'delivery_condition.required' => 'Bạn phải nhập điều kiện giao hàng.',
                'payment_condition.required' => 'Bạn phải nhập điều kiện thanh toán.',
                'tender_end_time.required' => 'Bạn phải nhập thời gian đóng thầu.',
            ];
            $request->validate($rules,$messages);

            //Check the Supplier is existed or not
            $tender = Tender::findOrFail($request->tender_id);
            $material_ids = QuantityAndDeliveryTime::where('tender_id', $tender->id)->pluck('material_id')->toArray();
            $supplier_ids = MaterialSupplier::whereIn('material_id', $material_ids)->pluck('supplier_id')->toArray();
            $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();
            if(!$suppliers->count()) {
                Alert::toast('Hàng hóa này chưa có nhà cung cấp! Bạn cần bổ sung nhà cung cấp.', 'error', 'top-right');
                return redirect()->route('admin.tenders.index');
            }

            $tender = Tender::findOrFail($id);
            $tender->title = $request->title;
            $tender->origin = $request->origin;
            $tender->packing = $request->packing;
            $tender->delivery_condition = $request->delivery_condition;
            $tender->payment_condition = $request->payment_condition;
            $tender->certificate = $request->certificate;
            $tender->other_term = $request->other_term;
            $tender->freight_charge = $request->freight_charge;
            $tender->creator_id = Auth::user()->id;
            $tender->status = 'Mở';
            $tender->tender_end_time = Carbon::parse($request->tender_end_time);
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
            if('Mở' == $tender->status) {
                //Destroy all TenderSuppliersSelectedStatus
                $tender_suppliers_selected_statuses = TenderSuppliersSelectedStatus::where('tender_id', $id)->get();
                foreach($tender_suppliers_selected_statuses as $item){
                    $item->destroy($item->id);
                }
                $tender->destroy($id);
                Alert::toast('Xóa tender thành công!', 'success', 'top-right');
            } else {
                Alert::toast('Tender không ở trạng thái Mở nên không thể xóa!', 'error', 'top-right');
            }
            return redirect()->route('admin.tenders.index');
        }else {
            Alert::toast('Bạn không có quyền xóa tender!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
    }

    public function anyData()
    {
        $tenders = Tender::with('creator')->orderBy('id', 'desc')->select(['id', 'title', 'tender_end_time', 'creator_id', 'status'])->get();
        return Datatables::of($tenders)
            ->addIndexColumn()
            ->editColumn('titlelink', function ($tenders) {
                return '<a href="'.route('admin.tenders.show', $tenders->id).'">'.$tenders->title.'</a>';
            })
            ->editColumn('status', function ($tenders) {
                if($tenders->status == 'Mở') {
                    return '<span class="badge badge-primary">Mở</span>';

                } else if($tenders->status == 'Đóng'){
                    return '<span class="badge badge-success">Đóng</span>';
                } else if($tenders->status == 'Hủy'){
                    return '<span class="badge badge-secondary">Hủy</span>';
                } else if($tenders->status == 'Đang kiểm tra'){
                    return '<span class="badge badge-info">Đang kiểm tra</span>';
                } else {
                    return '<span class="badge badge-warning">Đang diễn ra</span>';
                }
            })
            ->editColumn('tender_end_time', function ($tenders) {
                return date('d/m/Y H:i', strtotime($tenders->tender_end_time));
            })
            ->addColumn('actions', function ($tenders) {
                $action = '';
                if(Auth::user()->can('change-status')){
                    $action = $action . '<a href="' . route("admin.tenders.changeStatus", $tenders->id) . '" class="btn btn-success btn-sm"><i class="fas fa-random"></i></a>';
                }
                if(Auth::user()->can('edit-tender')){
                    $action = $action . ' <a href="' . route("admin.tenders.edit", $tenders->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>';
                }
                if(Auth::user()->can('cancel-tender')){
                    $action = $action . ' <a href="' . route("admin.tenders.getCancelTender", $tenders->id) . '" class="btn btn-secondary btn-sm"><i class="fas fa-ban"></i></a>';
                }
                if(Auth::user()->can('destroy-tender')){
                    $action = $action . '<form style="display:inline" action="'. route("admin.tenders.destroy", $tenders->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                }

                return $action;
            })
            ->rawColumns(['titlelink', 'status', 'actions', 'change_status'])
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
            //Check status is Hủy
            if('Hủy' == $tender->status) {
                Alert::toast('Tender đang ở trạng thái Hủy. Bạn không thể chuyển trạng thái!', 'warning', 'top-right');
                return redirect()->back();
            }
            $tender->status = $request->status;
            $tender->checker_id = Auth::user()->id;
            if(null != $request->is_competitive_bids){
                $tender->is_competitive_bids = true;
            }

            //Send notification email to suppliers and some admins
            if('Đang diễn ra' == $request->status) {
                //Get the user's mail list
                $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
                $users = User::whereIn('supplier_id', $selected_supplier_ids)->get();
                foreach($users as $user)  {
                    //Notify now
                    Notification::route('mail' , $user->email)->notify(new TenderInProgress($tender->id));
                    //Send reminder to customers 15 minutes before the Tender ends
                    $end_time = Carbon::parse($tender->tender_end_time);
                    $delay = $end_time->addMinutes(-15);
                    Notification::route('mail' , $user->email)->notify((new ReminderTenderInProgress($tender->id))->delay($delay));
                }

                //Send mail to Trưởng phòng Thu Mua
                $admins = Admin::all();
                foreach($admins as $admin) {
                    if('Trưởng phòng Thu Mua' == $admin->role->name){
                        Notification::route('mail' , $admin->email)->notify(new TenderInProgress($tender->id));
                    }
                }


                //Update the in-progress time
                $tender->tender_in_progress_time = Carbon::now();
                //Save the tender
                $tender->save();

                Alert::toast('Tender chuyển sang trạng thái Đang Diễn Ra. Bắt đầu đấu thầu!', 'success', 'top-right');
                return redirect()->route('admin.tenders.index');
            }else{
                Alert::toast('Không cho phép chuyển về trạng thái khác!', 'error', 'top-right');
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
        $materials = Material::all()->pluck('name', 'id');
        return view('admin.tender.create_quantity_and_delivery_times', ['tender' => $tender, 'materials' => $materials]);
    }

    public function storeQuantityAndDeliveryTimes(Request $request)
    {
        $rules = [
            'addmore.*.material_id' => 'required',
            'addmore.*.quantity' => 'required',
            'addmore.*.quantity_unit' => 'required',
            'addmore.*.delivery_time' => 'required',
        ];
        $messages = [
            'addmore.*.material_id.required' => 'Bạn phải nhập tên hàng.',
            'addmore.*.quantity.required' => 'Bạn phải nhập số lượng.',
            'addmore.*.quantity_unit.required' => 'Bạn phải chọn đơn vị.',
            'addmore.*.delivery_time.required' => 'Bạn phải nhập thời gian giao hàng.',
        ];
        $request->validate($rules,$messages);

        $tender = $request->session()->get('tender');

        foreach ($request->addmore as $key => $value) {
            //Check the Supplier is existed or not
            $supplier_ids = MaterialSupplier::where('material_id' ,'=' ,$value['material_id'])->pluck('supplier_id')->toArray();
            $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();
            if(0 == $suppliers->count()) {
                Alert::toast('Hàng hóa này chưa có nhà cung cấp! Bạn cần bổ sung nhà cung cấp.', 'error', 'top-right');
                return redirect()->back();
            }

            QuantityAndDeliveryTime::create($value);
        }

        $request->session()->put('tender', $tender);
        return redirect()->route('admin.tenders.createSuppliers');
    }


    public function editQuantityAndDeliveryTimes(Request $request)
    {
        $tender = $request->session()->get('tender');
        $materials = Material::all()->pluck('name', 'id');
        $quantity_and_delivery_times = QuantityAndDeliveryTime::where('tender_id', $tender->id)->get();
        return view('admin.tender.edit_quantity_and_delivery_times',
                     ['tender' => $tender,
                    'quantity_and_delivery_times' => $quantity_and_delivery_times,
                    'materials' => $materials
                     ]);
    }

    public function updateQuantityAndDeliveryTimes(Request $request)
    {
        $rules = [
            'addmore.*.material_id' => 'required',
            'addmore.*.quantity' => 'required',
            'addmore.*.quantity_unit' => 'required',
            'addmore.*.delivery_time' => 'required',
        ];
        $messages = [
            'addmore.*.material_id.required' => 'Bạn phải nhập tên hàng.',
            'addmore.*.quantity.required' => 'Bạn phải nhập số lượng.',
            'addmore.*.quantity_unit.required' => 'Bạn phải chọn đơn vị.',
            'addmore.*.delivery_time.required' => 'Bạn phải nhập thời gian giao hàng.',
        ];
        $request->validate($rules,$messages);

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
        $material_ids = QuantityAndDeliveryTime::where('tender_id', $tender->id)->pluck('material_id')->toArray();
        $supplier_ids = MaterialSupplier::whereIn('material_id', $material_ids)->pluck('supplier_id')->toArray();
        $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();

        //TODO: need to be considered again???
        if(!$suppliers->count()) {
            Alert::toast('Hàng hóa này chưa có nhà cung cấp! Bạn cần bổ sung nhà cung cấp.', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        } else {
            return view('admin.tender.createsuppliers', ['suppliers' => $suppliers, 'tender' => $tender]);
        }
    }

    public function storeSuppliers(Request $request)
    {
        $is_users_not_existed = false;
        $supplier_id_not_has_users = 0;

        $tender = $request->session()->get('tender');
        //Delete all old TenderSuppliersSelectedStatus
        $old_supplier_selected_statuses = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->get();
        foreach($old_supplier_selected_statuses as $item) {
            $item->destroy($item->id);
        }

        //Store the selected status of suppliers
        $material_ids = QuantityAndDeliveryTime::where('tender_id', $tender->id)->pluck('material_id')->toArray();
        $supplier_ids = MaterialSupplier::whereIn('material_id', $material_ids)->pluck('supplier_id')->toArray();
        $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();
        //dd($suppliers);
        foreach($suppliers as $key => $value){
            //Check if supplier has any user
            $users = User::where('supplier_id', $value->id)->get();
            if(0 == $users->count()
            && in_array($value->id, $request->supplier_ids)){
                $is_users_not_existed = true;
                $supplier_id_not_has_users = $value->id;
                break;
            }else{
                $supplier_selected_status = new TenderSuppliersSelectedStatus;
                $supplier_selected_status->tender_id = $tender->id;
                $supplier_selected_status->supplier_id = $value->id;
                //dd(in_array($value->id, $request->supplier_ids));
                $supplier_selected_status->is_selected = in_array($value->id, $request->supplier_ids);// ? 1:0;//isset($request->supplier_ids[$key]);
                $supplier_selected_status->reason = $request->reasons[$key];
                $supplier_selected_status->save();
            }
        }

        if(true == $is_users_not_existed){
            $supplier = Supplier::findOrFail($supplier_id_not_has_users);
            Alert::toast($supplier->name . ' chưa được tạo tài khoản tender. Vui lòng kiểm tra lại! ', 'error', 'top-right');
            return redirect()->back();
        }else{
            //Send email notification
            $tender->notify(new TenderCreated($tender->id));

            Alert::toast('Tạo tender thành công!', 'success', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
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

            Alert::toast('Chọn kết quả thầu thành công!', 'success', 'top-right');
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

            Alert::toast('Xóa kết quả thầu thành công!', 'success', 'top-right');
            return redirect()->route('admin.tenders.createResult', $tender->id);
        }else{
            Alert::toast('Bạn không có quyền xóa kết quả thầu!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function createPropose(Request $request, $id)
    {
        if(Auth::user()->can('update-tender')){

            if(null == $request->propose){
                //Delete all old Propose
                $old_proposes = TenderPropose::where('tender_id', $id)->get();
                foreach($old_proposes as $propose){
                    $propose->destroy($propose->id);
                }

                Alert::toast('Xóa đề xuất cũ thành công!', 'success', 'top-right');
                return redirect()->back();
            }else{
                //Delete all old Propose
                $old_proposes = TenderPropose::where('tender_id', $id)->get();
                foreach($old_proposes as $propose){
                    $propose->destroy($propose->id);
                }
                //Create new Propose
                $propose = new TenderPropose();
                $propose->tender_id = $id;
                $propose->propose = $request->propose;
                $propose->save();

                Alert::toast('Tạo đề xuất thành công!', 'success', 'top-right');
                return redirect()->back();
            }
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
            if('Đóng' != $tender->status) {
                $propose->destroy($id);
                Alert::toast('Xóa đề xuất thành công!', 'success', 'top-right');
            } else {
                Alert::toast('Tender đã đóng. Không thể xóa đề xuất!', 'error', 'top-right');
            }
            return redirect()->back();
        }else {
            Alert::toast('Bạn không có quyền xóa đề xuất này!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function createRequestApprove($id)
    {
        $tender = Tender::findOrFail($id);
        $approvers = Admin::where('role_id', 2)->get();
        return view('admin.tender.request_approve', ['tender' => $tender, 'approvers' => $approvers]);
    }

    public function storeRequestApprove(Request $request, $id)
    {
        $rules = [
            'manager_id' => 'required',
        ];
        $messages = [
            'manager_id.required' => 'Bạn phải nhập tên người duyệt.',
        ];
        $request->validate($rules,$messages);

        //Update tender
        $tender = Tender::findOrFail($id);
        $tender->manager_id = $request->manager_id;
        $tender->save();

        $manager = Admin::findOrFail($tender->manager_id);
        //Send email notification
        Notification::route('mail' , $manager->email)->notify(new TenderRequestApprove($tender->id));

        Alert::toast('Gửi yêu cầu duyệt kết quả Tender thành công!', 'success', 'top-right');
        return redirect()->route('admin.tenders.show', $tender->id);
    }

    public function requestAudit($id)
    {
        $tender = Tender::findOrFail($id);
        //Send email notification to Auditor
        $tender->notify(new TenderRequestAudit($tender->id));
        Alert::toast('Yêu cầu kiểm tra kết quả Tender đã được gửi tới Kiểm Soát Nội Bộ thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function getAudit($id)
    {
        $tender = Tender::findOrFail($id);
        if(Auth::user()->can('audit-result')){
            return view('admin.tender.audit', ['tender' => $tender]);
        }else{
            Alert::toast('Bạn không có quyền kiểm tra kết quả tender này!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function auditResult(Request $request, $id)
    {
        $tender = Tender::findOrFail($id);

        if(Auth::user()->can('audit-result')){
            $rules = [
                'audit_result' => 'required',
            ];
            $messages = [
                'audit_result.required' => 'Bạn phải chọn Đồng ý hoặc Từ chối',
            ];
            $request->validate($rules,$messages);

            //Update tender audit result
            $tender = Tender::findOrFail($id);
            $tender->audit_result = $request->audit_result;
            $tender->auditor_id = Auth::user()->id;
            $tender->save();

            Alert::toast('Kiểm tra kết quả thầu thành công!', 'success', 'top-right');
            return redirect()->route('admin.tenders.show', $tender->id);
        }else{
            Alert::toast('Bạn không có quyền kiểm tra kết quả thầu!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
    }

    public function getApproveResult($id)
    {
        $tender = Tender::findOrFail($id);
        if($tender->manager_id == Auth::user()->id
            && $tender->manager_id == Auth::user()->id){
            return view('admin.tender.approve', ['tender' => $tender]);
        }else{
            Alert::toast('Bạn không có quyền phê duyệt tender này!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function approveResult(Request $request, $id)
    {
        $tender = Tender::findOrFail($id);

        if($tender->manager_id == Auth::user()->id
            && $tender->manager_id == Auth::user()->id){
            $rules = [
                'approve_result' => 'required',
            ];
            $messages = [
                'approve_result.required' => 'Bạn phải chọn Đồn ý hoặc Từ chối',
            ];
            $request->validate($rules,$messages);

            //Update tender approve result
            $tender = Tender::findOrFail($id);
            $tender->approve_result = $request->approve_result;
            $tender->save();

            //Create new comment
            $comment = new TenderApproveComment();
            $comment->tender_id = $id;
            $comment->comment = $request->approve_comments;
            $comment->save();

            //Send email notification
            $admins = Admin::where('role_id', 3)->orWhere('role_id', 4)->get();
            foreach($admins as $admin){
                Notification::route('mail' , $admin->email)->notify(new TenderApproved($tender->id));
            }

            //Close the tender and send tender result to Users
            if('Đồng ý' == $tender->approve_result){
                //Update status
                $tender->status = 'Đóng';
                $tender->tender_closed_time = Carbon::now();
                $tender->save();

                //Get the mail list
                $bided_user_ids = [];
                $bided_user_ids = Bid::where('tender_id', $tender->id)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $bided_user_ids)->get();
                foreach($users as $user)  {
                    Notification::route('mail' , $user->email)->notify(new TenderResult($tender->id));
                }

                Alert::toast('Tender chuyển sang trạng thái Đóng. Kết thúc đấu thầu!', 'success', 'top-right');
                return redirect()->route('admin.tenders.index');
            }
            Alert::toast('Duyệt kết quả thầu thành công!', 'success', 'top-right');
            return redirect()->route('admin.tenders.index');
        }else{
            Alert::toast('Bạn không có quyền duyệt kết quả thầu!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
    }

    public function getCancelTender($id)
    {
        if(Auth::user()->can('cancel-tender')){
            $tender = Tender::findOrFail($id);
            if('Hủy' == $tender->status){
                Alert::toast('Tender đã ở trạng thái Hủy!', 'error', 'top-right');
                return redirect()->back();
            }
            return view('admin.tender.cancel', ['tender' => $tender]);
        }else{
            Alert::toast('Bạn không có quyền hủy tender này!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function postCancelTender(Request $request, $id)
    {
        $tender = Tender::findOrFail($id);

        if(Auth::user()->can('cancel-tender')){
            $rules = [
                'cancel_reason' => 'required',
            ];
            $messages = [
                'cancel_reason.required' => 'Bạn phải chọn nhập lý do hủy tender',
            ];
            $request->validate($rules,$messages);

            //Update tender approve result
            $tender = Tender::findOrFail($id);
            $tender->status = "Hủy";
            $tender->cancel_reason = $request->cancel_reason;
            $tender->save();

            //Send email notification
            //Get the mail list
            $selected_supplier_ids = TenderSuppliersSelectedStatus::where('tender_id', $tender->id)->where('is_selected', 1)->pluck('supplier_id')->toArray();
            $users = User::whereIn('supplier_id', $selected_supplier_ids)->get();
            foreach($users as $user)  {
                //Notify now
                Notification::route('mail' , $user->email)->notify(new TenderCanceled($tender->id));
            }

            Alert::toast('Hủy tender thành công!', 'success', 'top-right');
            return redirect()->route('admin.tenders.index');
        }else{
            Alert::toast('Bạn không có hủy tender này!', 'error', 'top-right');
            return redirect()->route('admin.tenders.index');
        }
    }
}
