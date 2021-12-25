@section('title')
{{ 'Gửi kết quả thầu' }}
@endsection

@extends('layouts.base')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-8">
          <h1 class="m-0">Kết quả: {{$tender->title}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-4">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.tenders.show', $tender->id) }}">Chi tiết tender</a></li>
            <li class="breadcrumb-item active">Chọn kết quả thầu</li>
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
                <div class="pull-right">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_result">
                        <i class="fas fa-plus"></i> Thêm
                    </button>
                </div>
                <br>
                <table id="bids-table" class="table table-bordered table-striped">
                  <tr>
                    <th>Nhà cung cấp</th>
                    <th>Số lượng chọn</th>
                    <th>Giá</th>
                    <th>Thời gian giao</th>
                    <th>Xóa</th>
                  </tr>
                  @foreach ($selected_bids as $bid)
                  <tr>
                    <td style="width:40%;">{{$bid->user->supplier->name}} ({{ $bid->user->email }})</td>
                    <td>{{ number_format($bid->tender_quantity, 0, '.', ' ') }} ({{$bid->tender_quantity_unit}})</td>
                    @if('đồng/kg' == $bid->price_unit
                        || 'đồng/chiếc' == $bid->price_unit)
                    <td>{{ number_format($bid->price, 0, ',', ' ') }} ({{$bid->price_unit}})</td>
                    @else
                    <td>{{ number_format($bid->price, 2, ',', ' ') }} ({{$bid->price_unit}})</td>
                    @endif
                    <td>{{ $bid->delivery_time }}</td>
                    <td>
                        <form action="{{route('admin.tenders.destroyResult', $bid->id)}}" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" name="submit" value="Xóa" class="btn btn-danger" onClick="return confirm(\'Bạn có chắc chắn muốn xóa?\')">

                            {{csrf_field()}}
                            @method('delete')
                       </form>
                    </td>
                  </tr>
                  @endforeach
                </table>
              </div>
            </div>
        </div>
      </div>
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
    <form class="form-horizontal" method="post" action="{{ route('admin.tenders.sendResult', $tender->id) }}" name="send_result" id="send_result" novalidate="novalidate">
        {{ csrf_field() }}
        <div class="modal fade" id="add_result">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <label class="required-field" class="control-label">Số lượng trúng thầu</label>
                                <div class="input-group">
                                    <input type="number" name="tender_quantity" id="tender_quantity" placeholder="0" class="form-control" />
                                    <select name="tender_quantity_unit" id="tender_quantity_unit" class="form-control" style="max-width:15%;">
                                        <option value="tấn" selected>tấn</option>
                                        <option value="kg">kg</option>
                                        <option value="chiếc">chiếc</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Giá</label>
                                    <div class="controls">
                                        <select name="bid_id" id="bid_id" class="form-control select2">
                                            @foreach ($bids as $bid)
                                                <option value="{{$bid->id}}">{{$bid->user->supplier->name}} | {{$bid->price}} ({{$bid->price_unit}}) | {{$bid->delivery_time}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
    </form>
    <!-- /.modal -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
