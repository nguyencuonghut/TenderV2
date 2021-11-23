<?php

namespace App\Http\Controllers;

use Datatables;
use App\Models\Supplier;
use Illuminate\Http\Request;

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
        $suppliers = Supplier::with('users')->select(['id', 'code', 'name'])->get();
        return Datatables::of($suppliers)
            ->addIndexColumn()
            ->editColumn('code', function ($suppliers) {
                return $suppliers->code;
            })
            ->editColumn('name', function ($suppliers) {
                return $suppliers->name;
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
            ->addColumn('edit', function ($suppliers) {
                return '<a href="' . route("admin.suppliers.edit", $suppliers->id) . '" class="btn btn-warning"> Sửa</a>';
            })
            ->addColumn('delete', '
                <form action="{{ route(\'admin.suppliers.destroy\', $id) }}" method="POST">
                     <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" name="submit" value="Xóa" class="btn btn-danger" onClick="return confirm(\'Bạn có chắc chắn muốn xóa?\')"">

                    {{csrf_field()}}
                </form>')
            ->rawColumns(['edit', 'delete'])
            ->make(true);
    }
}
