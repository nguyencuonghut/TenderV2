@section('title')
{{ 'Hồ sơ của tôi' }}
@endsection

@push('styles')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@extends('layouts.base')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Hồ sơ của tôi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Trang chủ</a></li>
              <li class="breadcrumb-item active">Hồ sơ của tôi</li>
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
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">

                    <h3 class="profile-username text-center">{{Auth::user()->name}}</h3>

                    <p class="text-muted text-center">{{Auth::user()->email}}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Số tenders</b> <a class="float-right">{{$tenders_cnt}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Số lần chào thầu</b> <a class="float-right">{{$bids_cnt}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Số lần trúng thầu</b> <a class="float-right">{{$selected_bids_cnt}}</a>
                    </li>
                    </ul>

                    <a href="{{route('user.change.password.get')}}" class="btn btn-warning btn-block"><b>Đổi mật khẩu</b></a>
                </div>
                <!-- /.card-body -->
                </div>
                </div>
                <!-- /.card -->


            <!-- About Me Box -->
            @if($recent_bids->count())
            <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Hoạt động gần đây</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  @foreach($recent_bids as $bid)
                  <strong><i class="fas fa-clock mr-1"></i> {{date('d/m/Y H:i', strtotime($bid->created_at))}}</strong>

                  <p class="text-muted">
                    Đấu thầu {{$bid->quantity->material->name}} : {{number_format($bid->quantity->quantity, 0, '.', ',')}} {{$bid->quantity->quantity_unit}}
                  </p>
                  <p class="text-muted">
                    Chào thầu:
                    {{$bid->price}} ({{$bid->price_unit}})
                  </p>

                  <hr>
                  @endforeach
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            @endif
            </div>
            <div class="col-md-9">
                <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Tất cả tender</h5>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="bids-table" class="table">
                                <thead>
                                <tr>
                                 <th>#</th>
                                  <th>Tender</th>
                                  <th>Tên hàng</th>
                                  <th>Gói thầu</th>
                                  <th>Giá</th>
                                  <th>Xuất xứ</th>
                                  <th>Kết quả</th>
                                </tr>
                                </thead>
                              </table>
                        </div>
                    <!-- /.table-responsive -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- ./card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection



@push('scripts')
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>



<style type="text/css">
    .dataTables_wrapper .dt-buttons {
    margin-bottom: -3em
  }
</style>


<script>
    $(function () {
      $("#bids-table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        buttons: [
            {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                }
            },
            /*
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                }
            },
            */
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                }
            }
        ],
        dom: 'Blfrtip',
        ajax: ' {!! route('user.bids.data') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'titlelink', name: 'title'},
            {data: 'material', name: 'material'},
            {data: 'quantity_and_delivery_id', name: 'quantity_and_delivery_id'},
            {data: 'price', name: 'price'},
            {data: 'origin', name: 'origin'},
            {data: 'is_selected', name: 'is_selected'},
       ]
      }).buttons().container().appendTo('#bids-table_wrapper .col-md-6:eq(0)');
    });
  </script>
@endpush

