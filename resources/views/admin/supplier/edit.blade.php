@section('title')
{{ 'Sửa nhà cung cấp' }}
@endsection

@push('styles')
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa nhà cung cấp</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.index') }}">Tất cả nhà cung cấp</a></li>
              <li class="breadcrumb-item active">Sửa nhà cung cấp</li>
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
                    <form class="form-horizontal" method="post" action="{{ route('admin.suppliers.update', $supplier->id) }}" name="edit_supplier" id="edit_supplier" novalidate="novalidate">
                        @method('PATCH')
                        {{ csrf_field() }}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Mã</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="code" id="code" value="{{ $supplier->code }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Tên</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="name" id="name" value="{{ $supplier->name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Hàng hóa</label>
                                        <div class="controls">
                                            <select name="material_id[]" id="material_id[]" data-placeholder="Chọn hàng hóa" class="form-control select2" multiple="multiple">
                                                @foreach($materials as $item)
                                                    <option value="{{$item->id}}" @if(in_array($item->id, $selected_materials)) selected="selected" @endif>{{$item->code}} - {{$item->name}}</option>
                                                @endforeach
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

@push('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
        theme: 'bootstrap4'
        })
    })
</script>
@endpush
