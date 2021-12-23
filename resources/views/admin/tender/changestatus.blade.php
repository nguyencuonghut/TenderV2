@section('title')
{{ 'Chuyển trạng thái' }}
@endsection

@push('styles')
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

   <style>
    input[type="checkbox"] {
        pointer-events: none;
    }
    </style>
@endpush

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Chuyển trạng thái tender</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.tenders.index') }}">Tất cả tender</a></li>
              <li class="breadcrumb-item active">Chuyển trạng thái</li>
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
                    <form class="form-horizontal" method="post" action="{{ route('admin.tenders.updateStatus', $tender->id) }}" name="update_status" id="update_status" novalidate="novalidate">
                        @method('PATCH')
                        {{ csrf_field() }}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Trạng thái</label>
                                        <div class="controls">
                                            <select name="status" id="status" class="form-control select2">
                                                <option value="Open" @if("Open" == $tender->status) selected @endif>Open</option>
                                                <option value="In-progress" @if("In-progress" == $tender->status) selected @endif>In-progress</option>
                                                <option value="Closed" @if("Closed" == $tender->status) selected @endif>Closed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                  <label class="required-field" class="control-label">Nhà thầu</label>
                                  <!-- checkbox -->
                                  <div class="form-group">
                                    @foreach($suppliers as $supplier)
                                    <div class="form-check">
                                      <input class="form-check-input" type="checkbox"
                                        name="supplier_ids[]" value="{{$supplier->id}}"
                                        @if(in_array($supplier->id, $selected_supplier_ids))
                                            checked
                                        @endif
                                      >
                                      @if(in_array($supplier->id, $bided_supplier_ids))
                                        <i class="fas fa-envelope fa-sm" style="color:#007BFF;"></i>
                                        @else
                                        <i class="far fa-envelope fa-sm"></i>
                                        @endif
                                      @if(in_array($supplier->id, $selected_bided_supplier_ids))
                                        <i class="fas fa-certificate fa-sm" style="color:#007BFF;"></i>
                                        @else
                                        <i class="far fa-certificate fa-sm"></i>
                                        @endif
                                      <label class="form-check-label">{{$supplier->name}}</label>
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
