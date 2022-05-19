@section('title')
{{ 'Chi tiết tender' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ $tender->title }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('user.tenders.index') }}">Tất cả tender</a></li>
              <li class="breadcrumb-item active">Chi tiết tender</li>
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
                <div class="col-12 col-sm-12">
                    <div class="card card-primary card-outline card-tabs">
                      <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Chi tiết</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Chào hàng</a>
                          </li>
                          @if($selected_bids->count()
                                && $tender->status == 'Closed')
                          <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-profile-tab-1" data-toggle="pill" href="#custom-tabs-one-profile-1" role="tab" aria-controls="custom-tabs-one-profile-1" aria-selected="false">Kết quả</a>
                          </li>
                          @endif
                        </ul>
                      </div>
                      <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade" id="custom-tabs-one-profile-1" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab-1">
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="bids-table" class="table table-bordered table-striped">
                                        <tr>
                                          <th>Lượng trúng</th>
                                          <th>Giá</th>
                                          <th>Thời gian giao</th>
                                        </tr>
                                        @foreach ($selected_bids as $bid)
                                        <tr>
                                          <td>{{ number_format($bid->tender_quantity, 0, '.', ' ') }} {{$bid->tender_quantity_unit}}</td>
                                          @if('đồng/kg' == $bid->price_unit
                                              || 'đồng/chiếc' == $bid->price_unit)
                                          <td>{{ number_format($bid->price, 0, ',', ' ') }} ({{$bid->price_unit}})</td>
                                          @else
                                          <td>{{ number_format($bid->price, 2, ',', ' ') }} ({{$bid->price_unit}})</td>
                                          @endif
                                          <td>{{ $bid->delivery_time }}</td>
                                        </tr>
                                        @endforeach
                                      </table>
                                    </div>
                                  </div>
                            </div>

                          <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Thời gian bắt đầu</strong><br>
                                            {{date('d/m/Y H:i', strtotime($tender->tender_start_time))}}<br>
                                          </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Thời gian kết thúc</strong><br>
                                            {{date('d/m/Y H:i', strtotime($tender->tender_end_time));}}<br>
                                          </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Tên hàng</strong><br>
                                            {{$tender->material->name}} (Xuất xứ: {{$tender->origin}})<br>
                                          </address>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Số lượng và thời gian giao hàng</strong><br>
                                            @foreach ($quantity_and_delivery_times as $item)
                                                - {{$item->quantity}} {{$item->quantity_unit}} ({{$item->delivery_time}})<br>
                                            @endforeach                                          </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Đóng gói</strong><br>
                                            {!! $tender->packing !!}<br>
                                          </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Điều kiện giao hàng</strong><br>
                                            {!! $tender->delivery_condition !!}<br>
                                          </address>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                    <hr>
                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Điều kiện thanh toán</strong><br>
                                            {!!$tender->payment_condition!!}<br>
                                          </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Chứng từ cung cấp</strong><br>
                                            {!! $tender->certificate !!}<br>
                                          </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Các điều kiện khác</strong><br>
                                            {!! $tender->other_term !!}<br>
                                          </address>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                    <hr>
                                    <div class="row invoice-info">
                                        <div class="col-sm-12 invoice-col">
                                          <address>
                                            <strong>Yêu cầu chất lượng</strong><br>
                                            {!!$tender->material->quality!!}<br>
                                          </address>
                                        </div>
                                    </div>
                                </div>

                                <!--
                                @if(Carbon\Carbon::now()->lessThan($tender->tender_end_time))
                                    <div class="card-footer clearfix">
                                        <a href="{{route('user.bids.index', $tender->id)}}">
                                            <button role="button" type="button" class="btn btn-success float-right"><i class="fas fa-gavel"></i> Đấu thầu</button>
                                        </a>
                                    </div>
                                @else
                                    <div class="card-footer clearfix">
                                        <a href="{{route('user.tenders.index')}}">
                                            <button role="button" type="button" class="btn btn-secondary float-right"><i class="fas fa-gavel"></i> Tender hết hạn</button>
                                        </a>
                                    </div>
                                @endif
                                -->
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                  @if(Carbon\Carbon::now()->lessThan($tender->tender_end_time))
                                  <div class="pull-right">
                                      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_bid">
                                          <i class="fas fa-plus"></i> Thêm
                                      </button>
                                  </div>
                                  <br>
                                  @endif
                                  @if($bids->count())
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
                                      <th style="Width: 8%;">Xóa</th>
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
                                          <form action="{{ route('user.bids.destroy', $bid->id) }}" method="POST">
                                              <input type="hidden" name="_method" value="DELETE">
                                              <input type="submit" name="submit" value="Xóa" class="btn btn-danger" onClick="return confirm(\'Bạn có chắc chắn muốn xóa?\')">

                                              {{csrf_field()}}
                                              @method('delete')
                                         </form>
                                      </td>
                                      @endif
                                    </tr>
                                    @endforeach
                                  </table>
                                  @endif
                                </div>
                              </div>

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
                                                            <label class="required-field" class="control-label">Lượng yêu cầu</label>
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
                                        <!-- /.modal-content -->
                                    </div>
                                </div>
                              </form>
                              <!-- /.modal -->
                        </div>
                        </div>
                      </div>
                      <!-- /.card -->
                    </div>
                  </div>
            </div>
        </div>
    </section>
@endsection
