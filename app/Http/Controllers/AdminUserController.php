<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\User;
use Datatables;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all()->pluck('name', 'id');
        return view('admin.user.create', ['suppliers' => $suppliers]);
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:6',
            'supplier_id' => 'required',
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
            'supplier_id.required' => 'Bạn phải nhập tên công ty.',
        ];
        $request->validate($rules,$messages);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->supplier_id = $request->supplier_id;
        $user->save();

        Alert::toast('Tạo người dùng mới thành công!', 'success', 'top-right');
        return redirect()->route('admin.users.index');
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
        $user = User::findOrFail($id);
        $suppliers = Supplier::all()->pluck('name', 'id');
        return view('admin.user.edit', ['user' => $user, 'suppliers' => $suppliers]);
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'confirmed',
            'supplier_id' => 'required',
        ];
        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.max' => 'Tên dài quá 255 ký tự.',
            'type.required' => 'Bạn phải nhập kiểu người dùng.',
            'email.required' => 'Bạn phải nhập địa chỉ email.',
            'email.email' => 'Email sai định dạng.',
            'email.max' => 'Email dài quá 255 ký tự.',
            'password.confirmed' => 'Mật khẩu không khớp.',
            'supplier_id.required' => 'Bạn phải nhập tên công ty.',
        ];
        $request->validate($rules,$messages);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if(null != $request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->supplier_id = $request->supplier_id;
        $user->save();
        Alert::toast('Cập nhật thông tin thành công!', 'success', 'top-right');
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->destroy($id);
        Alert::toast('Xóa người dùng thành công!', 'success', 'top-right');
        return redirect()->route('admin.users.index');
    }

    public function anyData()
    {
        $users = User::with('supplier')->select(['id', 'name', 'email', 'supplier_id'])->get();
        return Datatables::of($users)
            ->addIndexColumn()
            ->editColumn('name', function ($users) {
                return $users->name;
            })
            ->editColumn('email', function ($users) {
                return $users->email;
            })
            ->editColumn('supplier_id', function ($users) {
                return $users->supplier->name;
            })
            ->addColumn('edit', function ($users) {
                return '<a href="' . route("admin.users.edit", $users->id) . '" class="btn btn-warning"> Sửa</a>';
            })
            ->addColumn('delete', '
                <form action="{{ route(\'admin.users.destroy\', $id) }}" method="POST">
                     <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" name="submit" value="Xóa" class="btn btn-danger" onClick="return confirm(\'Bạn có chắc chắn muốn xóa?\')"">

                    {{csrf_field()}}
                </form>')
            ->rawColumns(['edit', 'delete'])
            ->make(true);
    }
}
