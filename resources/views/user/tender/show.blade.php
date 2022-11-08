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
          <div class="col-sm-8">
            <h1 class="m-0">{{ $tender->title }}</h1>
            @if(Carbon\Carbon::now()->lessThan($tender->tender_end_time))
            <div class="wrap-countdown mercado-countdown" data-expire="{{ Carbon\Carbon::parse($tender->tender_end_time)->format('Y/m/d H:i:s') }}"></div>
            @endif

          </div><!-- /.col -->
          <div class="col-sm-4">
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
                                && $tender->status == 'Đóng')
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
                                          <td>{{ number_format($bid->tender_quantity, 0, '.', ',') }} {{$bid->tender_quantity_unit}}</td>
                                          <td>{{ $bid->price }} ({{$bid->price_unit}})</td>
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
                                            <strong>Xuất xứ</strong><br>
                                            {{$tender->origin}}<br>
                                          </address>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row invoice-info">
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
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                            <address>
                                              <strong>Điều kiện thanh toán</strong><br>
                                              {!!$tender->payment_condition!!}<br>
                                            </address>
                                          </div>
                                    </div>
                                    <!-- /.row -->

                                    <hr>
                                    <div class="row invoice-info">
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
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                            <address>
                                                <strong>Ghi chú cước vận tải</strong><br>
                                                {!!$tender->freight_charge!!}<br>
                                            </address>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                    <hr>
                                    <div class="col-sm-12 invoice-col">
                                        <address>
                                          <strong>Số lượng và thời gian giao hàng</strong><br>
                                          <table id="quantity-and-delivery-table" class="table table-bordered table-hover">
                                            <tr>
                                              <th>Tên hàng</th>
                                              <th>Số lượng</th>
                                              <th>Thời gian giao</th>
                                            </tr>
                                            @foreach ($quantity_and_delivery_times as $item)
                                            <tr>
                                              <td>{{$item->material->name}}</td>
                                              <td>{{number_format($item->quantity, 0, '.', ',')}} {{$item->quantity_unit}}</td>
                                              <td>{{$item->delivery_time}}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                        </address>
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
                                      @if($is_rating)
                                      <th>Xếp hạng</th>
                                      @endif
                                      @if('Đóng' != $tender->status
                                            && Carbon\Carbon::now()->lessThan($tender->tender_end_time))
                                      <th style="Width: 11%;">Thao tác</th>
                                      @endif
                                    </tr>
                                    @foreach ($bids as $bid)
                                    <tr>
                                      <td>
                                        - {{$bid->quantity->material->name}} <br>
                                        - {{number_format($bid->quantity->quantity, 0, '.', ',')}} {{$bid->quantity->quantity_unit}} <br>
                                        - {{$bid->quantity->delivery_time}}</td>
                                      <td>
                                        - {{number_format($bid->bid_quantity, 0, '.', ',')}} {{$bid->bid_quantity_unit}} <br>
                                        - {{$bid->delivery_time}}
                                      </td>
                                      <td>{{ $bid->price }} ({{$bid->price_unit}})</td>
                                      <td>{{ $tender->origin }}</td>
                                      <td>{{ $bid->pack }}</td>
                                      <td>{!! $tender->delivery_condition !!}</td>
                                      <td>{!! $tender->payment_condition !!}</td>
                                      @php
                                        $all_current_bid_prices = App\Models\Bid::where('tender_id', $tender->id)->where('quantity_id', $bid->quantity_id)->orderBy('price', 'asc')->pluck('price')->toArray();
                                        $my_key = array_search($bid->price, $all_current_bid_prices);
                                      @endphp
                                      @if($is_rating)
                                      <td>{{$my_key + 1}} | {{sizeof($all_current_bid_prices)}} bids</td>
                                      @endif
                                      @if('Đóng' != $tender->status
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
                                  @endif
                                </div>
                              </div>

                              <form class="form-horizontal" method="post" action="{{ route('user.bids.create', $tender->id) }}" name="create_bid" id="create_bid" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="modal fade" id="add_bid">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4>Gửi chào giá</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="control-group">
                                                            <label class="required-field" class="control-label">Lượng yêu cầu</label>
                                                            <div class="input-group">
                                                                <select name="quantity_id" id="quantity_id" class="form-control">
                                                                    @foreach ($quantity_and_delivery_times as $item)
                                                                    <option value="{{$item->id}}" @if(in_array($item->id, $existed_qty_ids)) disabled @else selected @endif>{{number_format($item->quantity, 0, '.', ',')}} {{$item->quantity_unit}} {{\Illuminate\Support\Str::limit($item->material->name, 30)}} | {{$item->delivery_time}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
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
                                                                    <option value="đồng/chiếc">đồng/chiếc</option>
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
                                                                <input type="text" class="form-control" name="origin" id="origin" required="" disabled value="{{$tender->origin}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="control-group">
                                                            <label class="control-label">Điều kiện thanh toán</label>
                                                            <div class="controls">
                                                                <input type="text" class="form-control" name="payment_condition" id="payment_condition" required="" disabled value="{{$tender->payment_condition}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="control-group">
                                                            <label class="control-label">Địa điểm giao</label>
                                                            <div class="controls">
                                                                <input type="text" class="form-control" name="delivery_place" id="delivery_place" required="" disabled value="{{$tender->delivery_condition}}">
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
                                            <button type="submit" class="btn btn-primary">Gửi</button>
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
</div>
@endsection


@push('scripts')
<script src="{{ asset('plugins/jquery-countdown-timer/jquery.countdown.min.js') }}"></script>
<script>
   ;(function($) {

    var MERCADO_JS = {
        init: function(){
            this.mercado_countdown();
        },
    mercado_countdown: function() {
        if($(".mercado-countdown").length > 0){
            $(".mercado-countdown").each( function(index, el){
                var _this = $(this),
                _expire = _this.data('expire');
                _this.countdown(_expire, function(event) {
                    $(this).html( event.strftime('<span><i style="color:red" class="fas fa-stopwatch"></i> <b style="color:purple;">%D</b> Ngày</span> <span><b style="color:purple;">%-H</b> Giờ</span> <span><b style="color:purple;">%M</b> Phút</span> <span><b style="color:purple;">%S</b> Giây</span>'));
                });
            });
        }
      },

   }

    window.onload = function () {
        MERCADO_JS.init();
    }

    })(window.Zepto || window.jQuery, window, document);
</script>
@endpush
