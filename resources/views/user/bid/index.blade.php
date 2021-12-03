@section('title')
{{ 'Bỏ thầu' }}
@endsection

@extends('layouts.base')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">{{$tender->title}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('user.tenders.show', $tender->id) }}">Tender</a></li>
            <li class="breadcrumb-item active">Bỏ thầu</li>
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
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_bid">
                        <i class="fas fa-plus"></i> Thêm
                    </button>
                </div>
                <br>
                <table id="bids-table" class="table table-bordered table-striped">
                  <tr>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Đóng gói</th>
                    <th>Thời gian giao</th>
                    <th>Địa điểm giao</th>
                    <th>Điều kiện thanh toán</th>
                    <th>Ghi chú</th>
                    <th>Xóa</th>
                  </tr>
                  @foreach ($bids as $bid)
                  <tr>
                    <td>{{ number_format($bid->quantity, 0, '.', ' ') }} ({{$bid->quantity_unit}})</td>
                    @if('đồng/kg' == $bid->price_unit
                        || 'đồng/chiếc' == $bid->price_unit)
                    <td>{{ number_format($bid->price, 0, ',', ' ') }} ({{$bid->price_unit}})</td>
                    @else
                    <td>{{ number_format($bid->price, 2, ',', ' ') }} ({{$bid->price_unit}})</td>
                    @endif
                    <td>{{ $bid->pack }}</td>
                    <td>{{ $bid->delivery_time }}</td>
                    <td>{{ $bid->delivery_place }}</td>
                    <td>{{ $bid->payment_condition }}</td>
                    <td>{{ $bid->note }}</td>
                    <td>
                        <form action="{{ route('user.bids.destroy', $bid->id) }}" method="POST">
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
    <form class="form-horizontal" method="post" action="{{ route('user.bids.create', $tender->id) }}" name="create_bid" id="create_bid" novalidate="novalidate">
        {{ csrf_field() }}
        <div class="modal fade" id="add_bid">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label class="required-field" class="control-label">Số lượng</label>
                        <div class="input-group">
                            <input type="number" name="quantity" id="quantity" placeholder="0" class="form-control" />
                            <select name="quantity_unit" id="quantity_unit" class="form-control" style="max-width:15%;">
                                <option value="tấn" selected>tấn</option>
                                <option value="kg">kg</option>
                                <option value="chiếc">chiếc</option>
                            </select>
                        </div>

                        <label class="required-field" class="control-label">Giá</label>
                        <div class="input-group">
                            <input type="number" name="price" id="price" placeholder="0" step="any" class="form-control" />
                            <select name="price_unit" id="price_unit" class="form-control" style="max-width:15%;">
                                <option value="đồng/kg" selected>đồng/kg</option>
                                <option value="USD/tấn">USD/tấn</option>
                                <option value="USD/kg">USD/kg</option>
                                <option value="đồng/tấn">đồng/chiếc</option>
                            </select>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Ghi chú</label>
                            <div class="controls">
                                <input type="text" class="form-control" name="note" id="note" required="">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Đóng gói</label>
                            <div class="controls">
                                <input type="text" class="form-control" name="pack" id="pack" required="">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Thời gian giao</label>
                            <div class="controls">
                                <input type="text" class="form-control" name="delivery_time" id="delivery_time" required="">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Địa điểm giao</label>
                            <div class="controls">
                                <input type="text" class="form-control" name="delivery_place" id="delivery_place" required="">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Điều kiện thanh toán</label>
                            <div class="controls">
                                <input type="text" class="form-control" name="payment_condition" id="payment_condition" required="">
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
