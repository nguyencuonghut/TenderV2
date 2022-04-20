<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Role;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AdminAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->can('create-admin')) {
            $roles = Role::all();
            return view('admin.admin.create', ['roles' => $roles]);
        } else {
            Alert::toast('Bạn không có quyền tạo tài khoản!', 'error', 'top-right');
            return redirect()->route('admin.admins.index');
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
        if(Auth::user()->can('store-admin')) {
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|confirmed|min:6',
                'role_id' => 'required',
            ];
            $messages = [
                'name.required' => 'Bạn phải nhập tên.',
                'name.max' => 'Tên dài quá 255 ký tự.',
                'type.required' => 'Bạn phải nhập kiểu người dùng.',
                'email.required' => 'Bạn phải nhập địa chỉ email.',
                'email.email' => 'Email sai định dạng.',
                'email.max' => 'Email dài quá 255 ký tự.',
                'password.required' => 'Bạn phải nhập mật khẩu.',
                'password.min' => 'Mật khẩu phải dài ít nhất 6 ký tự.',
                'password.confirmed' => 'Mật khẩu không khớp.',
                'role_id.required' => 'Bạn phải chọn vai trò.',
            ];
            $request->validate($rules,$messages);

            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = bcrypt($request->password);
            $admin->role_id = $request->role_id;
            $admin->save();

            Alert::toast('Tạo tài khoản mới thành công!', 'success', 'top-right');
            return redirect()->route('admin.admins.index');
        } else {
            Alert::toast('Bạn không có quyền tạo tài khoản!', 'error', 'top-right');
            return redirect()->route('admin.admins.index');
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
        if(Auth::user()->can('edit-admin')) {
            $admin = Admin::findOrFail($id);
            $roles = Role::all();
            return view('admin.admin.edit', ['admin' => $admin, 'roles' => $roles]);
        } else {
            Alert::toast('Bạn không có quyền sửa tài khoản!', 'error', 'top-right');
            return redirect()->route('admin.admins.index');
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
        if(Auth::user()->can('update-admin')) {
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'password' => 'confirmed|',
                'role_id' => 'required',
            ];
            $messages = [
                'name.required' => 'Bạn phải nhập tên.',
                'name.max' => 'Tên dài quá 255 ký tự.',
                'type.required' => 'Bạn phải nhập kiểu người dùng.',
                'email.required' => 'Bạn phải nhập địa chỉ email.',
                'email.email' => 'Email sai định dạng.',
                'email.max' => 'Email dài quá 255 ký tự.',
                'password.confirmed' => 'Mật khẩu không khớp.',
                'role_id.required' => 'Bạn phải chọn vai trò.',
            ];
            $request->validate($rules,$messages);

            $admin = Admin::findOrFail($id);
            $admin->name = $request->name;
            $admin->email = $request->email;
            if(null != $request->password) {
                $admin->password = bcrypt($request->password);
            }
            $admin->role_id = $request->role_id;
            $admin->save();
            Alert::toast('Cập nhật thông tin thành công!', 'success', 'top-right');
            return redirect()->route('admin.admins.index');
        } else {
            Alert::toast('Bạn không có quyền sửa tài khoản!', 'error', 'top-right');
            return redirect()->route('admin.admins.index');
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
        if(Auth::user()->can('destroy-admin')) {
            $admin = Admin::findOrFail($id);
            $admin->destroy($id);
            Alert::toast('Xóa người dùng thành công!', 'success', 'top-right');
            return redirect()->route('admin.admins.index');

        } else {
            Alert::toast('Bạn không có quyền xóa tài khoản!', 'error', 'top-right');
            return redirect()->route('admin.admins.index');
        }
    }

    public function anyData()
    {
        $admins = Admin::select(['id', 'name', 'email'])->get();
        return Datatables::of($admins)
            ->addIndexColumn()
            ->editColumn('name', function ($admins) {
                return $admins->name;
            })
            ->editColumn('email', function ($admins) {
                return $admins->email;
            })
            ->addColumn('edit', function ($admins) {
                return '<a href="' . route("admin.admins.edit", $admins->id) . '" class="btn btn-warning"> Sửa</a>';
            })
            ->addColumn('delete', '
                <form action="{{ route(\'admin.admins.destroy\', $id) }}" method="POST">
                     <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" name="submit" value="Xóa" class="btn btn-danger" onClick="return confirm(\'Bạn có chắc chắn muốn xóa?\')"">

                    {{csrf_field()}}
                </form>')
            ->rawColumns(['edit', 'delete'])
            ->make(true);
    }
}
