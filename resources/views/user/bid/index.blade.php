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
                    @if(Carbon\Carbon::now()->lessThan($tender->tender_end_time))
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_bid">
                        <i class="fas fa-plus"></i> Thêm
                    </button>
                    @endif
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
                    @if($is_rating)
                    <th>Xếp hạng</th>
                    @endif
                    @if('Đóng' != $tender->status
                        && Carbon\Carbon::now()->lessThan($tender->tender_end_time))
                    <th style="width: 10%;">Thao tác</th>
                    @endif
                  </tr>
                  @foreach ($bids as $bid)
                  <tr>
                    <td>
                        - {{\Illuminate\Support\Str::limit($bid->quantity->material->name, 40)}} <br>
                        - {{number_format($bid->quantity->quantity, 0, '.', ',')}} {{$bid->quantity->quantity_unit}} <br>
                        - {{$bid->quantity->delivery_time}}
                    </td>
                    <td>
                        - {{number_format($bid->bid_quantity, 0, '.', ',')}} {{$bid->bid_quantity_unit}} <br>
                        @if($bid->delivery_time)
                        - {{$bid->delivery_time}}
                        @endif
                    </td>
                    <td>{{ $bid->price }} ({{$bid->price_unit}})</td>
                    <td>{{ $tender->origin }}
                        @if($bid->seller)
                          <br>
                          (Bên bán: {{$bid->seller}})
                        @endif
                    </td>
                    <td>{{ $bid->pack }}</td>
                    <td>{!! $tender->delivery_condition !!}</td>
                    <td>{!! $tender->payment_condition !!}</td>
                    @php
                        $all_current_bid_prices = App\Models\Bid::where('tender_id', $tender->id)->where('quantity_id', $bid->quantity_id)->orderBy('price', 'asc')->pluck('price')->toArray();
                        $my_key = array_search($bid->price, $all_current_bid_prices);
                    @endphp
                    @if($is_rating)
                    <td>@if(count(array_keys($all_current_bid_prices, $bid->price)) > 1)<i class="fas fa-layer-group" style="color:orange;"></i>@endif {{$my_key + 1}} | {{sizeof($all_current_bid_prices)}} bids</td>
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
                        <h4 class="modal-title">Gửi chào giá</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-8">
                                <div class="control-group">
                                    <label class="required-field" class="control-label">Số lượng</label>
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
                                    <label class="control-label">Bên bán</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="seller" id="seller" required="">
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
            </div>
        </div>
    </form>
    <!-- /.modal -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
