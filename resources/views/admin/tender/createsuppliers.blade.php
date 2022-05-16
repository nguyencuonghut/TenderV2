@section('title')
{{ 'Chọn nhà thầu' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Chọn nhà thầu</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.tenders.index') }}">Tất cả tender</a></li>
              <li class="breadcrumb-item active">Chọn nhà thầu</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                    <form class="form-horizontal" method="post" action="{{ route('admin.tenders.storeSuppliers') }}" name="store_suppliers" id="store_suppliers" novalidate="novalidate">
                        @method('PATCH')
                        {{ csrf_field() }}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                  <label class="required-field" class="control-label float-left">Nhà thầu</label>
                                  <label class="control-label float-right">Lý do (nếu không chọn)</label>
                                  <!-- checkbox -->
                                  <div class="form-group">
                                    @foreach($suppliers as $supplier)
                                    <div class="form-check">
                                      <input class="form-check-input" type="checkbox"
                                        name="supplier_ids[]" value="{{$supplier->id}}" checked>
                                      <label class="form-check-label">{{$supplier->name}}</label>
                                      <input name="reasons[]" type="text" style="float:right; height: 20px;width: 60%;">
                                    </div>
                                    @endforeach
                                  </div>
                                </div>
                            </div>

                            <br>
                            <div class="control-group">
                                <div class="controls">
                                    <input type="submit" value="Cập nhật" class="btn btn-success">
                                </div>
                            </div>
                        <div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

