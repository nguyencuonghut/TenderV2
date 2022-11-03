<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;

class AdminRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->can('create-role')){
            return view('admin.role.create');
        }else{
            Alert::toast('Bạn không có quyền tạo chức vụ!', 'error', 'top-right');
            return redirect()->route('admin.roles.index');
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
        if(Auth::user()->can('store-role')){
            $rules = [
                'name' => 'required',
            ];
            $messages = [
                'name.required' => 'Bạn phải nhập chức vụ.',
            ];
            $request->validate($rules,$messages);

            $role = new Role();
            $role->name = $request->name;
            $role->save();

            Alert::toast('Tạo mới thành công!', 'success', 'top-right');
            return redirect()->route('admin.roles.index');
        }else{
            Alert::toast('Bạn không có quyền tạo chức vụ!', 'error', 'top-right');
            return redirect()->route('admin.roles.index');
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
        if(Auth::user()->can('edit-role')){
            $role = Role::findOrFail($id);
            return view('admin.role.edit', ['role' => $role]);
        }else{
            Alert::toast('Bạn không có quyền sửa chức vụ!', 'error', 'top-right');
            return redirect()->route('admin.roles.index');
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
        if(Auth::user()->can('update-role')){
            $rules = [
                'name' => 'required',
            ];
            $messages = [
                'name.required' => 'Bạn phải nhập chức vụ.',
            ];
            $request->validate($rules,$messages);

            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();

            Alert::toast('Sửa thành công!', 'success', 'top-right');
            return redirect()->route('admin.roles.index');
        }else{
            Alert::toast('Bạn không có quyền cập nhật chức vụ!', 'error', 'top-right');
            return redirect()->route('admin.roles.index');
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
        if(Auth::user()->can('destroy-role')){
            $role = Role::findOrFail($id);
            //Check condition before destroying
            if($role->admins->count() == 0) {
                $role->destroy($id);
                Alert::toast('Xóa chức vụ thành công!', 'success', 'top-right');
                return redirect()->route('admin.roles.index');
            } else {
                Alert::toast('Chức vụ đang được gán cho người quản trị. Không thể xóa!', 'error', 'top-right');
                return redirect()->route('admin.roles.index');
            }
        }else{
            Alert::toast('Bạn không có quyền xóa chức vụ!', 'error', 'top-right');
            return redirect()->route('admin.roles.index');
        }
    }

    public function anyData()
    {
        $roles = Role::select(['id', 'name'])->get();
        return Datatables::of($roles)
            ->addIndexColumn()
            ->editColumn('name', function ($roles) {
                return $roles->name;
            })
            ->addColumn('actions', function ($roles) {
                $action = '<a href="' . route("admin.roles.edit", $roles->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("admin.roles.destroy", $roles->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
