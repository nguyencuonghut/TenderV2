<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\Supplier;
use App\Models\User;
use App\Notifications\UserCreated;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

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
        if(Auth::user()->can('create-user')){
            $suppliers = Supplier::all()->pluck('name', 'id');
            return view('admin.user.create', ['suppliers' => $suppliers]);
        }else {
            Alert::toast('Bạn không có quyền tạo tài khoản!', 'error', 'top-right');
            return redirect()->route('admin.users.index');
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
        if(Auth::user()->can('store-user')){
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'supplier_id' => 'required',
            ];
            $messages = [
                'name.required' => 'Bạn phải nhập tên.',
                'name.max' => 'Tên dài quá 255 ký tự.',
                'type.required' => 'Bạn phải nhập kiểu người dùng.',
                'email.required' => 'Bạn phải nhập địa chỉ email.',
                'email.email' => 'Email sai định dạng.',
                'email.max' => 'Email dài quá 255 ký tự.',
                'supplier_id.required' => 'Bạn phải nhập tên công ty.',
            ];
            $request->validate($rules,$messages);

            $password = Str::random(8);
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($password);
            $user->supplier_id = $request->supplier_id;
            $user->save();

            //Send password to user's email
            Notification::route('mail' , $user->email)->notify(new UserCreated($user->id, $password));

            Alert::toast('Tạo người dùng mới và gửi mật khẩu tới email họ thành công!', 'success', 'top-right');
            return redirect()->route('admin.users.index');
        }else {
            Alert::toast('Bạn không có quyền tạo tài khoản!', 'error', 'top-right');
            return redirect()->route('admin.users.index');
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
        if(Auth::user()->can('edit-user')){
            $user = User::findOrFail($id);
            $suppliers = Supplier::all()->pluck('name', 'id');
            return view('admin.user.edit', ['user' => $user, 'suppliers' => $suppliers]);
        }else {
            Alert::toast('Bạn không có quyền sửa tài khoản!', 'error', 'top-right');
            return redirect()->route('admin.users.index');
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
        if(Auth::user()->can('update-user')){
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'supplier_id' => 'required',
            ];
            $messages = [
                'name.required' => 'Bạn phải nhập tên.',
                'name.max' => 'Tên dài quá 255 ký tự.',
                'type.required' => 'Bạn phải nhập kiểu người dùng.',
                'email.required' => 'Bạn phải nhập địa chỉ email.',
                'email.email' => 'Email sai định dạng.',
                'email.max' => 'Email dài quá 255 ký tự.',
                'supplier_id.required' => 'Bạn phải nhập tên công ty.',
            ];
            $request->validate($rules,$messages);

            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->supplier_id = $request->supplier_id;
            $user->save();
            Alert::toast('Cập nhật thông tin thành công!', 'success', 'top-right');
            return redirect()->route('admin.users.index');
        }else {
            Alert::toast('Bạn không có quyền sửa tài khoản!', 'error', 'top-right');
            return redirect()->route('admin.users.index');
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
        if(Auth::user()->can('destroy-user')){
            $user = User::findOrFail($id);
            //Check condition before destroying
            if($user->bids->count() == 0) {
                $user->forceDelete($id);
                Alert::toast('Xóa người dùng thành công!', 'success', 'top-right');
                return redirect()->route('admin.users.index');
            } else {
                //Soft delete the User
                $user->destroy($id);
                Alert::toast('Người dùng đang có thông tin đấu thầu.Tài khoản người dùng này sẽ chuyển sang trạng thái đình chỉ!', 'success', 'top-right');
                return redirect()->route('admin.users.index');
            }
        }else {
            Alert::toast('Bạn không có quyền xóa tài khoản!', 'error', 'top-right');
            return redirect()->route('admin.users.index');
        }
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
            ->addColumn('actions', function ($users) {
                $action = '<a href="' . route("admin.users.edit", $users->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("admin.users.destroy", $users->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function import(Request $request)
    {
        try{
            $import = new UsersImport;
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
