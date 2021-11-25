<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Material;
use App\Models\MaterialSupplier;
use App\Models\Supplier;
use Datatables;
use App\Models\Tender;
use App\Notifications\TenderCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $materials = Material::all()->pluck('name', 'id');
        $creators = Admin::all()->pluck('name', 'id');
        $suppliers = Supplier::all()->pluck('name', 'id');
        return view('admin.tender.create', ['materials' => $materials, 'creators' => $creators, 'suppliers' => $suppliers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'material_id' => 'required',
            'quantity_and_delivery_time' => 'required',
            'quality' => 'required',
            'delivery_condition' => 'required',
            'payment_condition' => 'required',
            'certificate' => 'required',
            'date_range' => 'required',
        ];
        $messages = [
            'title.required' => 'Bạn phải nhập tiêu đề.',
            'material_id.required' => 'Bạn phải nhập tên hàng.',
            'quantity_and_delivery_time.required' => 'Bạn phải nhập số lượng và thời gian giao hàng.',
            'quality.required' => 'Bạn phải nhập chất lượng.',
            'delivery_condition.required' => 'Bạn phải nhập điều kiện giao hàng.',
            'payment_condition.required' => 'Bạn phải nhập điều kiện thanh toán.',
            'certificate.required' => 'Bạn phải nhập chứng từ cung cấp.',
            'date_range.required' => 'Bạn phải nhập thời gian áp dụng.',
        ];
        $request->validate($rules,$messages);

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
        $tender->quantity_and_delivery_time = $request->quantity_and_delivery_time;
        $tender->quality = $request->quality;
        $tender->delivery_condition = $request->delivery_condition;
        $tender->payment_condition = $request->payment_condition;
        $tender->certificate = $request->certificate;
        $tender->other_term = $request->other_term;
        $tender->creator_id = Auth::user()->id;
        $tender->status = 'Open';
        $tender->supplier_ids = '';

        //Parse date range
        $dates = explode(' - ', $request->date_range);
        $tender->tender_start_time = Carbon::parse($dates[0]);
        $tender->tender_end_time = Carbon::parse($dates[1]);
        $tender->save();

        $request->session()->put('tender', $tender);
        return redirect()->route('admin.tenders.createSuppliers');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tender = Tender::findOrFail($id);
        if('Open' == $tender->status) {
            $tender->destroy($id);
            Alert::toast('Xóa tender thành công!', 'success', 'top-right');
        } else {
            Alert::toast('Tender đang diễn ra. Không thể xóa!', 'error', 'top-right');
        }
        return redirect()->route('admin.tenders.index');
    }

    public function anyData()
    {
        $tenders = Tender::with('creator')->with('material')->orderBy('id', 'desc')->select(['id', 'title', 'material_id', 'tender_start_time', 'tender_end_time', 'creator_id', 'status', 'supplier_ids'])->get();
        return Datatables::of($tenders)
            ->addIndexColumn()
            ->editColumn('title', function ($tenders) {
                return $tenders->title;
            })
            ->editColumn('material_id', function ($tenders) {
                return $tenders->material->name;
            })
            ->editColumn('tender_start_time', function ($tenders) {
                return $tenders->tender_start_time;
            })
            ->editColumn('tender_end_time', function ($tenders) {
                return $tenders->tender_end_time;
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
            ->rawColumns(['edit', 'delete', 'change_status'])
            ->make(true);
    }

    /*
    public function getSuppliers($materialId)
    {
        $supplier_ids = MaterialSupplier::where('material_id' ,'=' ,$materialId)->pluck('supplier_id')->toArray();
        $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();
        return response()->json($suppliers);
    }
    */

    public function changeStatus($id)
    {
        $tender = Tender::findOrFail($id);
        $supplier_ids = MaterialSupplier::where('material_id' ,'=' ,$tender->material_id)->pluck('supplier_id')->toArray();
        $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();

        $selected_supplier_ids = [];
        $selected_supplier_ids = explode(",", $tender->supplier_ids);
        return view('admin.tender.changestatus', ['tender' => $tender, 'suppliers' => $suppliers, 'selected_supplier_ids' => $selected_supplier_ids]);
    }

    public function updateStatus(Request $request, $id)
    {
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
        $tender->status = $request->status;
        /*
        $tender->supplier_ids = implode(',', $request->supplier_ids);
        */
        $tender->save();

        Alert::toast('Cập nhật trạng thái tender thành công!', 'success', 'top-right');
        return redirect()->route('admin.tenders.index');
    }

    public function createSuppliers(Request $request)
    {
        $tender = $request->session()->get('tender');
        $supplier_ids = MaterialSupplier::where('material_id' ,'=' ,$tender->material_id)->pluck('supplier_id')->toArray();
        $suppliers = Supplier::whereIn('id', $supplier_ids)->orderBy('id', 'asc')->get();

        return view('admin.tender.createsuppliers', ['suppliers' => $suppliers, 'tender' => $tender]);
    }

    public function storeSuppliers(Request $request)
    {
        $tender = $request->session()->get('tender');
        $updatedTender = Tender::findOrFail($tender->id);
        $updatedTender->supplier_ids = implode(',', $request->supplier_ids);
        $updatedTender->save();

        //Send email notification
        $tender->notify(new TenderCreated($updatedTender->id));

        Alert::toast('Tạo tender thành công!', 'success', 'top-right');
        return redirect()->route('admin.tenders.index');
    }
}
