@section('title')
{{ 'Trang chủ' }}
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
            <h1 class="m-0">Admin Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Admin Dashboard</li>
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
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$all_tenders->count()}}</h3>

                <p>Tổng số tender</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-copy"></i>
              </div>
              <a href="{{route('admin.tenders.index')}}" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$completed_tenders->count()}}</h3>

                <p>Tender hoàn thành</p>
              </div>
              <div class="icon">
                <i class="ion ion-checkmark-circled"></i>
              </div>
              <a href="{{route('admin.tenders.index')}}" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$in_progress_tenders->count()}}</h3>

                <p>Tender đang diễn ra</p>
              </div>
              <div class="icon">
                <i class="ion ion-load-a"></i>
              </div>
              <a href="{{route('admin.tenders.index')}}" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$suppliers->count()}}</h3>

                <p>Số nhà cung cấp</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{route('admin.suppliers.index')}}" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>

        <!-- TABLE: LATEST ORDERS -->
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Tất cả tender</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tenders-table" class="table">
                        <thead>
                        <tr>
                          <th>Tiêu đề</th>
                          <th>Tên hàng</th>
                          <th>Trạng thái</th>
                          <th>Bắt đầu</th>
                          <th>Kết thúc</th>
                        </tr>
                        </thead>
                      </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->

        <!-- /.row -->
      </div><!-- /.container-fluid -->
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
      $("#tenders-table").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        buttons: [
            {
                extend: 'copy',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            },
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            },
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            }
        ],
        dom: 'Blfrtip',
        ajax: ' {!! route('admin.tenders.data') !!}',
        columns: [
            {data: 'titlelink', name: 'title'},
            {data: 'material_id', name: 'material_id'},
            {data: 'status', name: 'status'},
            {data: 'tender_start_time', name: 'tender_start_time'},
            {data: 'tender_end_time', name: 'tender_end_time'},
       ]
      }).buttons().container().appendTo('#tenders-table_wrapper .col-md-6:eq(0)');
    });
  </script>
@endpush
