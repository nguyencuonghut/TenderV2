<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Datatables;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

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
        return view('admin.material.create');
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
        $material = Material::findOrFail($id);
        return view('admin.material.edit', ['material' => $material]);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->destroy($id);
        Alert::toast('Xóa hàng hóa thành công!', 'success', 'top-right');
        return redirect()->route('admin.materials.index');
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
            ->addColumn('edit', function ($materials) {
                return '<a href="' . route("admin.materials.edit", $materials->id) . '" class="btn btn-warning"> Sửa</a>';
            })
            ->addColumn('delete', '
                <form action="{{ route(\'admin.materials.destroy\', $id) }}" method="POST">
                     <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" name="submit" value="Xóa" class="btn btn-danger" onClick="return confirm(\'Bạn có chắc chắn muốn xóa?\')"">

                    {{csrf_field()}}
                </form>')
            ->rawColumns(['quality', 'edit', 'delete'])
            ->make(true);
    }
}