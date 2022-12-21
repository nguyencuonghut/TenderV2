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
              <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang chủ</a></li>
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
            <div class="col-md-4">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">

                    <h3 class="profile-username text-center">{{Auth::user()->name}}</h3>

                    <p class="text-muted text-center">{{Auth::user()->email}}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        @if(Auth::user()->role->name == 'Nhân viên Mua Hàng')
                        <li class="list-group-item">
                            <b>Số tender tôi tạo</b> <a class="float-right">{{$my_tenders_cnt}}</a>
                        </li>
                        @endif
                        @if(Auth::user()->role->name == 'Nhân viên Kiểm Soát')
                        <li class="list-group-item">
                            <b>Số tender tôi kiểm tra</b> <a class="float-right">{{$my_audited_tenders_cnt}}</a>
                        </li>
                        @endif
                    </ul>

                    <a href="{{route('admin.change.password.get')}}" class="btn btn-warning btn-block"><b>Đổi mật khẩu</b></a>
                </div>
                <!-- /.card-body -->
                </div>
                </div>
                <!-- /.card -->


            <!-- About Me Box -->
            @if($recent_tenders->count())
            <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Hoạt động gần đây</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  @foreach($recent_tenders as $tender)
                  <strong><i class="fas fa-gavel mr-1" style="color:#ffc107;"></i> {{$tender->title}}</strong>

                  <p class="text-muted">
                    {{date('d/m/Y H:i',strtotime($tender->tender_in_progress_time))}} - {{date('d/m/Y H:i',strtotime($tender->tender_end_time))}} <br>
                  </p>

                  <p class="text-muted">
                  @if ($tender->creator_id == Auth::user()->id)
                    Tôi tạo tender lúc {{date('d/m/Y H:i',strtotime($tender->created_at))}} <br>
                  @endif

                  @if ($tender->auditor_id == Auth::user()->id
                    && $tender->tender_in_progress_time)
                    Tôi mời thầu lúc {{date('d/m/Y H:i',strtotime($tender->tender_in_progress_time))}} <br>
                  @endif

                  @if($tender->auditor_id == Auth::user()->id
                    && $tender->tender_closed_time)
                    Tôi trả kết quả lúc {{date('d/m/Y H:i',strtotime($tender->tender_closed_time))}}
                  @endif
                  </p>

                  <hr>
                  @endforeach
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            @endif
            </div>
            <div class="col-md-8">
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
                                    <th>Tiêu đề</th>
                                    <th>Trạng thái</th>
                                    <th>Thờ gian đóng thầu</th>
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
                    columns: [0,1,2,3]
                }
            },
            /*
            {
                extend: 'csv',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }

            },
            {
                extend: 'excel',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }
            },
            {
                extend: 'pdf',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }
            },
            */
            {
                extend: 'print',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }
            },
            {
                extend: 'colvis',
                footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }
            }
        ],
        dom: 'Blfrtip',
        ajax: ' {!! route('admin.tenders.data') !!}',
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'titlelink', name: 'title'},
            {data: 'status', name: 'status'},
            {data: 'tender_end_time', name: 'tender_end_time'},
       ]
      }).buttons().container().appendTo('#bids-table_wrapper .col-md-6:eq(0)');
    });
  </script>
@endpush

