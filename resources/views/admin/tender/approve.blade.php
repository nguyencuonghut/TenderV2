@section('title')
{{ 'Duyệt kết quả thầu' }}
@endsection

@push('styles')
  <!-- Summernote -->
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
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
          <h1 class="m-0">Duyệt kết quả: {{$tender->title}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-4">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.tenders.show', $tender->id) }}">Chi tiết tender</a></li>
            <li class="breadcrumb-item active">Duyệt kết quả</li>
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
                <form class="form-horizontal" method="post" action="{{ route('admin.tenders.approveResult', $tender->id) }}" name="approve_result" id="approve_result" novalidate="novalidate">{{ csrf_field() }}
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Mã</label>
                                    <div class="controls">
                                        <select name="approve_result" id="approve_result" class="form-control" style="width: 100%;">
                                            <option selected="selected" disabled>Chọn</option>
                                                <option value="Đồng ý">Đồng ý</option>
                                                <option value="Từ chối">Từ chối</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="control-group">
                                    <label class="control-label">Bình luận</label>
                                    <div class="controls">
                                        <textarea id="approve_comments" name="approve_comments">
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" value="Duyệt" class="btn btn-success">
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
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

<script>
    $(function () {
        // Summernote
        $('#approve_comments').summernote({
            height: 80,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })
    })

    //Remove <p> tag by <br> when enter new line
    $("#approve_comments").on("summernote.enter", function(we, e) {
        $(this).summernote("pasteHTML", "<br><br>");
        e.preventDefault();
    });
</script>
@endpush
