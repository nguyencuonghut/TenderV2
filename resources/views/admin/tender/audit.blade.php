@section('title')
{{ 'Kiểm tra kết quả thầu' }}
@endsection

@push('styles')
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@extends('layouts.base')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-8">
          <h1 class="m-0">Kiểm tra kết quả: {{$tender->title}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-4">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.tenders.show', $tender->id) }}">Chi tiết tender</a></li>
            <li class="breadcrumb-item active">Kiểm tra kết quả</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <form class="form-horizontal" method="post" action="{{ route('admin.tenders.auditResult', $tender->id) }}" name="audit_result" id="audit_result" novalidate="novalidate">{{ csrf_field() }}
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Mã</label>
                                    <div class="controls">
                                        <select name="audit_result" id="audit_result" class="form-control" style="width: 100%;">
                                            <option selected="selected" disabled>Chọn</option>
                                                <option value="Đồng ý">Đồng ý</option>
                                                <option value="Từ chối">Từ chối</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" value="Lưu" class="btn btn-success">
                            </div>
                        </div>
                    <div>
                </form>
              </div>
            </div>
        </div>
      </div>
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection


@push('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
@endpush
