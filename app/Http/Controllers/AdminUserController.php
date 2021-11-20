<?php

namespace App\Http\Controllers;

use App\Models\User;
use Datatables;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    public function anyData()
    {
        $users = User::select(['id', 'name', 'email'])->get();
        return Datatables::of($users)
            ->addIndexColumn()
            ->editColumn('name', function ($users) {
                return $users->name;
            })
            ->editColumn('email', function ($users) {
                return $users->email;
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
