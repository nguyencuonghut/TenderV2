@section('title')
{{ 'Khóa NCC' }}
@endsection

@push('styles')
@endpush

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Khóa/Mở nhà cung cấp</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.index') }}">Tất cả nhà cung cấp</a></li>
              <li class="breadcrumb-item active">Khóa/Mở</li>
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
                        <div class="card-body">
                            <h4>{{$supplier->name}}</h4>
                        </div>
                    <form class="form-horizontal" method="post" action="{{ route('admin.suppliers.postDisable', $supplier->id) }}" name="disable_supplier" id="disable_supplier" novalidate="novalidate">{{ csrf_field() }}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Khóa/Mở</label>
                                        <div class="controls">
                                            <select name="is_disabled" id="is_disabled" data-placeholder="Chọn trạng thái" class="form-control" style="width: 100%;">
                                                <option {{false == $supplier->is_disabled ? 'selected' : ''}} value="Mở">Mở</option>
                                                <option {{true == $supplier->is_disabled ? 'selected' : ''}} value="Khóa">Khóa</option>
                                            </select>
                                        </div>
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
