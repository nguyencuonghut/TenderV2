@section('title')
{{ 'Chào thầu' }}
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
            <li class="breadcrumb-item active">Chào thầu</li>
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
                    <th>Lượng và thời gian yêu cầu</th>
                    <th>Lượng và thời gian chào</th>
                    <th>Giá</th>
                    <th>Xuất xứ</th>
                    <th>Đóng gói</th>
                    <th>Địa điểm giao</th>
                    <th>Điều kiện thanh toán</th>
                    <th>Ghi chú</th>
                    @if('Closed' != $tender->status
                        && Carbon\Carbon::now()->lessThan($tender->tender_end_time))
                    <th style="width: 10%;">Thao tác</th>
                    @endif
                  </tr>
                  @foreach ($bids as $bid)
                  <tr>
                    <td>{{$bid->quantity->quantity}} {{$bid->quantity->quantity_unit}} - {{$bid->quantity->delivery_time}}</td>
                    <td>{{$bid->bid_quantity}} {{$bid->bid_quantity_unit}} - {{$bid->delivery_time}}</td>
                    @if('đồng/kg' == $bid->price_unit
                        || 'đồng/chiếc' == $bid->price_unit)
                    <td>{{ number_format($bid->price, 0, ',', ' ') }} ({{$bid->price_unit}})</td>
                    @else
                    <td>{{ number_format($bid->price, 2, ',', ' ') }} ({{$bid->price_unit}})</td>
                    @endif
                    <td>{{ $bid->origin }}</td>
                    <td>{{ $bid->pack }}</td>
                    <td>{{ $bid->delivery_place }}</td>
                    <td>{{ $bid->payment_condition }}</td>
                    <td>{{ $bid->note }}</td>
                    @if('Closed' != $tender->status
                        && Carbon\Carbon::now()->lessThan($tender->tender_end_time))
                    <td>
                        <a href="{{route('user.bids.edit', $bid->id)}}" class="btn btn-dark btn-sm"><i class="fas fa-pen"></i></a>
                        <form style="display:inline" action="{{ route('user.bids.destroy', $bid->id) }}" method="POST">
                          <input type="hidden" name="_method" value="DELETE">
                          <button type="submit" name="submit" onclick="return confirm('Bạn có muốn xóa?')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                          {{csrf_field()}}
                          @method('delete')
                        </form>
                    </td>
                    @endif
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
                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Số lượng</label>
                                    <div class="input-group">
                                        <select name="quantity_id" id="quantity_id" class="form-control">
                                            @foreach ($quantity_and_delivery_times as $item)
                                            <option value="{{$item->id}}" selected>{{$item->quantity}} {{$item->quantity_unit}} - {{$item->delivery_time}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Lượng chào</label>
                                    <div class="input-group">
                                        <input type="number" name="bid_quantity" id="bid_quantity" placeholder="0" step="any" class="form-control" />
                                        <select name="bid_quantity_unit" id="bid_quantity_unit" class="form-control" style="max-width:40%;">
                                            <option value="tấn" selected>tấn</option>
                                            <option value="kg">kg</option>
                                            <option value="chiếc">chiếc</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Giá</label>
                                    <div class="input-group">
                                        <input type="number" name="price" id="price" placeholder="0" step="any" class="form-control" />
                                        <select name="price_unit" id="price_unit" class="form-control" style="max-width:40%;">
                                            <option value="đồng/kg" selected>đồng/kg</option>
                                            <option value="USD/tấn">USD/tấn</option>
                                            <option value="USD/kg">USD/kg</option>
                                            <option value="đồng/tấn">đồng/chiếc</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="control-group">
                                    <label class="control-label">Đóng gói</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="pack" id="pack" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
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
                                    <label class="control-label">Thời gian giao</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="delivery_time" id="delivery_time" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="control-label">Địa điểm giao</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="delivery_place" id="delivery_place" required="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="control-label">Điều kiện thanh toán</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="payment_condition" id="payment_condition" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="control-label">Ghi chú</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="note" id="note" required="">
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
            </div>
        </div>
    </form>
    <!-- /.modal -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
