@section('title')
{{ 'Tạo tender' }}
@endsection

@push('styles')
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <!-- Summernote -->
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
@endpush

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tạo mới tender</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.tenders.index') }}">Tất cả tender</a></li>
              <li class="breadcrumb-item active">Tạo mới</li>
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
                    <form class="form-horizontal" method="post" action="{{ url('admin/tenders') }}" name="add_tender" id="add_tender" novalidate="novalidate">{{ csrf_field() }}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Tiêu đề</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="title" id="title" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="control-label">Xuất xứ</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="origin" id="origin" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="control-label">Đóng gói</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="packing" id="packing" required="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="control-group">
                                        <label class="control-label">Chứng từ cung cấp</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="certificate" id="certificate" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="control-group">
                                        <label class="control-label">Điều khoản khác</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="other_term" id="other_term" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Điều kiện giao hàng</label>
                                        <div class="controls">
                                            <textarea id="delivery_condition" name="delivery_condition">
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field" class="control-label">Điều kiện thanh toán</label>
                                        <div class="controls">
                                            <textarea id="payment_condition" name="payment_condition">
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="control-label">Ghi chú giá cước vận tải</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="freight_charge" id="freight_charge" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="required-field">Thời gian mở thầu</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="date_range" name="date_range">
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="control-group">
                                <div class="controls">
                                    <input type="submit" value="Thêm tender" class="btn btn-success">
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
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
        theme: 'bootstrap4'
        })

        //Date range picker with time picker
        $('#date_range').daterangepicker({
            timePicker: true,
            startDate: moment().startOf('hour').add(30, 'minutes'),
            endDate: moment().startOf('hour').add(60, 'minutes'),
            locale: {
                format: 'MM/DD hh:mm A'
            }
        })

        // Summernote
        $('#delivery_condition').summernote({
            height: 80,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })
        $('#payment_condition').summernote({
            height: 80,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
            ]
        })
    })

    //Remove <p> tag by <br> when enter new line
    $("#delivery_condition").on("summernote.enter", function(we, e) {
        $(this).summernote("pasteHTML", "<br><br>");
        e.preventDefault();
    });
    $("#payment_condition").on("summernote.enter", function(we, e) {
        $(this).summernote("pasteHTML", "<br><br>");
        e.preventDefault();
    });

    $('body').on('change', '#material_id', function() {
        loadOptionSuppliers($(this).val());
    });

    function loadOptionSuppliers(materialID) {
        $.ajax({
            method: "GET",
            url: '/admin/tenders/getSuppliers/'+materialID,
            dataType: 'json',
            data: {
                "_token":"{{ csrf_token() }}"
            },
            beforeSend: function() {},
            success: function(data) {
                console.log(data);
                var supplierList = data;
                var str = '<option value="0">Chọn nhà thầu</option>';
                $.each(supplierList, function(index, value) {
                    str = str + "<option value='" + value.id + "'>" + value.name + "</option>";
                });
                $("#suppliers").html(str);

            }
        })
    }
</script>
@endpush
