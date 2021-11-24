<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Material;
use Datatables;
use App\Models\Tender;
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
        return view('admin.tender.create', ['materials' => $materials, 'creators' => $creators]);
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

        //Parse date range
        $dates = explode(' - ', $request->date_range);
        $tender->tender_start_time = Carbon::parse($dates[0]);
        $tender->tender_end_time = Carbon::parse($dates[1]);
        $tender->save();

        Alert::toast('Tạo tender mới thành công!', 'success', 'top-right');
        return redirect()->route('admin.tenders.index');
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
        $tenders = Tender::with('creator')->with('material')->select(['id', 'title', 'material_id', 'tender_start_time', 'tender_end_time', 'creator_id', 'status'])->get();
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
            ->editColumn('creator_id', function ($tenders) {
                return $tenders->creator->name;
            })
            ->addColumn('edit', function ($tenders) {
                return '<a href="' . route("admin.tenders.edit", $tenders->id) . '" class="btn btn-warning"> Sửa</a>';
            })
            ->addColumn('delete', '
                <form action="{{ route(\'admin.tenders.destroy\', $id) }}" method="POST">
                     <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" name="submit" value="Xóa" class="btn btn-danger" onClick="return confirm(\'Bạn có chắc chắn muốn xóa?\')"">

                    {{csrf_field()}}
                </form>')
            ->rawColumns(['edit', 'delete'])
            ->make(true);
    }
}
