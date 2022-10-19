<?php

namespace App\Http\Controllers;

use App\Imports\SuppliersImport;
use App\Models\Bid;
use App\Models\Material;
use App\Models\MaterialSupplier;
use Datatables;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class AdminSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.supplier.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->can('create-supplier')) {
            $materials = Material::orderBy('name', 'asc')->get();
            return view('admin.supplier.create', ['materials' => $materials]);
        } else {
            Alert::toast('Bạn không có quyền tạo nhà cung cấp mới!', 'error', 'top-right');
            return redirect()->route('admin.suppliers.index');
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
        if(Auth::user()->can('store-supplier')) {
            $rules = [
                'code' => 'required|unique:suppliers',
                'name' => 'required|max:255',
                'material_id' => 'required'
            ];
            $messages = [
                'code.required' => 'Bạn phải nhập mã.',
                'code.unique' => 'Mã đã tồn tại.',
                'name.required' => 'Bạn phải nhập tên.',
                'name.max' => 'Tên dài quá 255 ký tự.',
                'material_id.required' => 'Bạn phải nhập hàng hóa.',
            ];
            $request->validate($rules,$messages);

            //Create new Supplier
            $supplier = new Supplier();
            $supplier->name = $request->name;
            $supplier->code = $request->code;
            $supplier->save();

            //Create new MaterialSupplier
            foreach($request->material_id as $key => $value){
                $material_supplier = new MaterialSupplier();
                $material_supplier->material_id = $value;
                $material_supplier->supplier_id = $supplier->id;
                $material_supplier->save();
            }

            Alert::toast('Tạo nhà cung cấp mới thành công!', 'success', 'top-right');
            return redirect()->route('admin.suppliers.index');
        } else {
            Alert::toast('Bạn không có quyền lưu nhà cung cấp!', 'error', 'top-right');
            return redirect()->route('admin.suppliers.index');
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
        $supplier = Supplier::findOrFail($id);
        return view('admin.supplier.show', ['supplier' => $supplier]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->can('edit-supplier')) {
            $supplier = Supplier::findOrFail($id);
            $materials = Material::orderBy('name', 'asc')->get();
            $selected_materials = MaterialSupplier::where('supplier_id', $id)->pluck('material_id')->toArray();
            return view('admin.supplier.edit', ['supplier' => $supplier,
                                                'materials' => $materials,
                                                 'selected_materials' => $selected_materials]);
        } else {
            Alert::toast('Bạn không có quyền sửa nhà cung cấp!', 'error', 'top-right');
            return redirect()->route('admin.suppliers.index');
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
        if(Auth::user()->can('update-supplier')) {
            $rules = [
                'code' => 'required',
                'name' => 'required|max:255',
                'material_id' => 'required'
            ];
            $messages = [
                'code.required' => 'Bạn phải nhập mã.',
                'name.required' => 'Bạn phải nhập tên.',
                'name.max' => 'Tên dài quá 255 ký tự.',
                'material_id.required' => 'Bạn phải nhập hàng hóa.',
            ];
            $request->validate($rules,$messages);

            //Update Supplier
            $supplier = Supplier::findOrFail($id);
            $supplier->name = $request->name;
            $supplier->code = $request->code;
            $supplier->save();

            //Update MaterialSupplier
            $old_material_suppliers = MaterialSupplier::where('supplier_id', $id)->get();
            foreach($old_material_suppliers as $item){
                $item->destroy($item->id);
            }
            //Create new MaterialSupplier
            foreach($request->material_id as $key => $value){
                $material_supplier = new MaterialSupplier();
                $material_supplier->material_id = $value;
                $material_supplier->supplier_id = $supplier->id;
                $material_supplier->save();
            }

            Alert::toast('Cập nhật thông tin thành công!', 'success', 'top-right');
            return redirect()->route('admin.suppliers.index');
        } else {
            Alert::toast('Bạn không có quyền sửa nhà cung cấp!', 'error', 'top-right');
            return redirect()->route('admin.suppliers.index');
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
        if(Auth::user()->can('destroy-supplier')) {
            $supplier = Supplier::findOrFail($id);
            //Check condition before destroying
            if($supplier->users->count() == 0) {
                $supplier->destroy($id);
                Alert::toast('Xóa nhà cung cấp thành công!', 'success', 'top-right');
                return redirect()->route('admin.suppliers.index');
            } else {
                Alert::toast('Nhà cung cấp đang chứa user. Không thể xóa!', 'error', 'top-right');
                return redirect()->route('admin.suppliers.index');
            }
        } else {
            Alert::toast('Bạn không có quyền xóa nhà cung cấp!', 'error', 'top-right');
            return redirect()->route('admin.suppliers.index');
        }
    }

    public function anyData()
    {
        $suppliers = Supplier::with('users')->select(['id', 'code', 'name'])->get();
        return Datatables::of($suppliers)
            ->addIndexColumn()
            ->editColumn('code', function ($suppliers) {
                return '<a href="'.route('admin.suppliers.show', $suppliers->id).'">'.$suppliers->code.'</a>';
            })
            ->editColumn('name', function ($suppliers) {
                return '<a href="'.route('admin.suppliers.show', $suppliers->id).'">'.$suppliers->name.'</a>';
            })
            ->addColumn('users', function ($suppliers) {
                $i = 0;
                $length = count($suppliers->users);
                $mail_list = '';
                foreach ($suppliers->users as $key => $user) {
                    if(++$i === $length) {
                        $mail_list =  $mail_list . $user->email;
                    } else {
                        $mail_list = $mail_list . $user->email . ', ';
                    }
                }
                return $mail_list;
            })
            ->addColumn('materials', function ($suppliers) {
                $i = 0;
                $length = count($suppliers->materials);
                $material_list = '';
                foreach ($suppliers->materials as $item) {
                    if(++$i === $length) {
                        $material_list =  $material_list . $item->name;
                    } else {
                        $material_list = $material_list . $item->name . '/ ';
                    }
                }
                return $material_list;
            })
            ->addColumn('actions', function($suppliers) {
                $action = '<a href="' . route("admin.suppliers.edit", $suppliers->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("admin.suppliers.destroy", $suppliers->id) . '" method="POST">
                           <input type="hidden" name="_method" value="DELETE">
                           <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                           <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;

            })
            ->rawColumns(['code','name', 'actions'])
            ->make(true);
    }

    public function bidData($supplier_id)
    {
        //Find all users for this supplier
        $users = User::where('supplier_id', $supplier_id)->get();

        $bids = Bid::orderBy('id', 'desc')->with(['tender', 'quantity'])->get();
        $supplier_bids = collect();
        foreach($bids as $bid) {
            foreach($users as $user) {
                if($bid->user_id == $user->id) {
                    $supplier_bids->push($bid);
                }
            }
        }
        return Datatables::of($supplier_bids)
            ->addIndexColumn()
            ->editColumn('titlelink', function ($bids) {
                return '<a href="'.route('admin.tenders.show', $bids->tender_id).'">' . '('. $bids->tender->code . ') ' .$bids->tender->title.'</a>';
            })
            ->editColumn('material', function ($bids) {
                return $bids->quantity->material->name;
            })
            ->editColumn('quantity_and_delivery_id', function ($bids) {
                return number_format($bids->quantity->quantity, 0, '.', ',') . '(' . $bids->quantity->quantity_unit . ')' . ' | ' . $bids->quantity->delivery_time;
            })
            ->editColumn('price', function ($bids) {
                return $bids->price . '(' . $bids->price_unit . ')';
            })
            ->editColumn('origin', function ($bids) {
                return $bids->origin;
            })
            ->editColumn('is_selected', function ($bids) {
                if($bids->tender->status == 'Đóng') {
                    if($bids->is_selected == 1) {
                        return '<span class="badge badge-success">Trúng</span>';

                    } else {
                        return '<span class="badge badge-danger">Trượt</span>';
                    }
                } else {
                    return '<span class="badge badge-warning">Đang xét</span>';
                }
            })
            ->rawColumns(['titlelink', 'is_selected'])
            ->make(true);
    }

    public function import(Request $request)
    {
        ini_set('max_execution_time', 600);
        try {
            $supplier_import = new SuppliersImport;
            Excel::import($supplier_import, $request->file('file')->store('files'));
            $rows = $supplier_import->getRowCount();
            $duplicates = $supplier_import->getDuplicateCount();
            if($duplicates){
                Alert::toast('Import '. $rows . ' dòng dữ liệu thành công! Có ' . $duplicates . ' dòng bị trùng lặp!', 'success', 'top-right');
            }else{
                Alert::toast('Import '. $rows . ' dòng dữ liệu thành công!', 'success', 'top-right');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Có lỗi xảy ra trong quá trình import dữ liệu. Vui lòng kiểm tra lại file!', 'error', 'top-right');
            return redirect()->back();
        }
    }
}
