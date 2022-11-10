@section('title')
{{ 'Sửa chào thầu' }}
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
            <li class="breadcrumb-item"><a href="{{ route('user.bids.index', $tender->id) }}">Tất cả</a></li>
            <li class="breadcrumb-item active">Sửa chào thầu</li>
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
                <form class="form-horizontal" method="post" action="{{ route('user.bids.update', $bid->id) }}" name="edit_bid" id="edit_bid" novalidate="novalidate">
                    @method('PATCH')
                    {{ csrf_field() }}
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Lượng yêu cầu</label>
                                    <div class="input-group">
                                        <select name="quantity_id" id="quantity_id" class="form-control">
                                            @foreach ($quantity_and_delivery_times as $item)
                                            <option value="{{$item->id}}" @if ($item->id == $bid->quantity_id) selected @else disabled @endif>{{number_format($item->quantity, 0, '.', ',')}} {{$item->quantity_unit}} - {{$item->delivery_time}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Lượng chào</label>
                                    <div class="input-group">
                                        <input type="number" name="bid_quantity" id="bid_quantity" value="{{$bid->bid_quantity}}" step="any" class="form-control" />
                                        <select name="bid_quantity_unit" id="bid_quantity_unit" class="form-control" style="max-width:40%;">
                                            <option value="tấn" @if ("tấn" == $bid->bid_quantity_unit) selected @endif>tấn</option>
                                            <option value="kg" @if ("kg" == $bid->bid_quantity_unit) selected @endif>kg</option>
                                            <option value="chiếc" @if ("chiếc" == $bid->bid_quantity_unit) selected @endif>chiếc</option>
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
                                        <input type="number" name="price" id="price" value="{{$bid->price}}" step="any" class="form-control" />
                                        <select name="price_unit" id="price_unit" class="form-control" style="max-width:40%;">
                                            <option value="đồng/kg" @if ("đồng/kg" == $bid->price_unit) selected @endif>đồng/kg</option>
                                            <option value="USD/tấn" @if ("USD/tấn" == $bid->price_unit) selected @endif>USD/tấn</option>
                                            <option value="USD/kg" @if ("USD/kg" == $bid->price_unit) selected @endif>USD/kg</option>
                                            <option value="đồng/chiếc" @if ("đồng/chiếc" == $bid->price_unit) selected @endif>đồng/chiếc</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="control-group">
                                    <label class="control-label">Đóng gói</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="pack" id="pack" value="{{$bid->pack}}" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="control-group">
                                    <label class="control-label">Xuất xứ</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="origin" id="origin" disabled value="{{$tender->origin}}" required="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="control-label">Điều kiện thanh toán</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="payment_condition" id="payment_condition" disabled value="{{$tender->payment_condition}}" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="control-label">Địa điểm giao</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="delivery_place" id="delivery_place" disabled value="{{$tender->delivery_condition}}" required="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="control-label">Thời gian giao</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="delivery_time" id="delivery_time" value="{{$bid->delivery_time}}" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="control-group">
                                    <label class="control-label">Bên bán</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="seller" id="seller" value="{{$bid->seller}}" required="">
                                    </div>
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
                                            <option value="{{$item->id}}" selected>{{number_format($item->quantity, 0, '.', ',')}} {{$item->quantity_unit}} - {{$item->delivery_time}}</option>
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
