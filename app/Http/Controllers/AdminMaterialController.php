<?php

namespace App\Http\Controllers;

use App\Imports\MaterialsImport;
use App\Models\Material;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;

class AdminMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.material.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->can('create-material')){
            return view('admin.material.create');
        }else{
            Alert::toast('Bạn không có quyền tạo hàng hóa!', 'error', 'top-right');
            return redirect()->route('admin.materials.index');
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
        if(Auth::user()->can('store-material')){
            $rules = [
                'code' => 'required',
                'name' => 'required',
                'quality' => 'required',
            ];
            $messages = [
                'code.required' => 'Bạn phải nhập mã.',
                'name.required' => 'Bạn phải nhập tên hàng.',
                'quality.required' => 'Bạn phải nhập tiêu chuẩn chất lượng.',
            ];
            $request->validate($rules,$messages);

            $material = new Material();
            $material->code = $request->code;
            $material->name = $request->name;
            $material->quality = $request->quality;
            $material->save();

            Alert::toast('Tạo mới thành công!', 'success', 'top-right');
            return redirect()->route('admin.materials.index');
        }else{
            Alert::toast('Bạn không có quyền tạo hàng hóa!', 'error', 'top-right');
            return redirect()->route('admin.materials.index');
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
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->can('edit-material')){
            $material = Material::findOrFail($id);
            return view('admin.material.edit', ['material' => $material]);
        }else{
            Alert::toast('Bạn không có quyền sửa hàng hóa!', 'error', 'top-right');
            return redirect()->route('admin.materials.index');
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
        if(Auth::user()->can('update-material')){
            $rules = [
                'code' => 'required',
                'name' => 'required',
                'quality' => 'required',
            ];
            $messages = [
                'code.required' => 'Bạn phải nhập mã.',
                'name.required' => 'Bạn phải nhập tên hàng.',
                'quality.required' => 'Bạn phải nhập tiêu chuẩn chất lượng.',
            ];
            $request->validate($rules,$messages);

            $material = Material::findOrFail($id);
            $material->code = $request->code;
            $material->name = $request->name;
            $material->quality = $request->quality;
            $material->save();

            Alert::toast('Sửa thành công!', 'success', 'top-right');
            return redirect()->route('admin.materials.index');
        }else{
            Alert::toast('Bạn không có quyền cập nhật hàng hóa!', 'error', 'top-right');
            return redirect()->route('admin.materials.index');
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
        if(Auth::user()->can('destroy-material')){
            $material = Material::findOrFail($id);
            //Check condition before destroying
            if($material->suppliers->count() == 0) {
                $material->destroy($id);
                Alert::toast('Xóa hàng hóa thành công!', 'success', 'top-right');
                return redirect()->route('admin.materials.index');
            } else {
                Alert::toast('Hàng hóa đang được gán cho nhà cung cấp. Không thể xóa!', 'error', 'top-right');
                return redirect()->route('admin.materials.index');
            }
        }else{
            Alert::toast('Bạn không có quyền xóa hàng hóa!', 'error', 'top-right');
            return redirect()->route('admin.materials.index');
        }
    }

    public function anyData()
    {
        $materials = Material::select(['id', 'code', 'name', 'quality'])->get();
        return Datatables::of($materials)
            ->addIndexColumn()
            ->editColumn('code', function ($materials) {
                return $materials->code;
            })
            ->editColumn('name', function ($materials) {
                return $materials->name;
            })
            ->editColumn('quality', function ($materials) {
                return $materials->quality;
            })
            ->addColumn('actions', function ($materials) {
                $action = '<a href="' . route("admin.materials.edit", $materials->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("admin.materials.destroy", $materials->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['quality', 'actions'])
            ->make(true);
    }

    public function import(Request $request)
    {
        try{
            $import = new MaterialsImport;
            Excel::import($import, $request->file('file')->store('files'));
            $rows = $import->getRowCount();
            $duplicates = $import->getDuplicateCount();
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
