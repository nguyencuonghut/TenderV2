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
            <!--<h1 class="m-0">{{ $tender->title }}</h1> -->
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.tenders.index') }}">Tất cả tender</a></li>
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
                          @if ($selected_bids->count())
                          <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-profile-tab-1" data-toggle="pill" href="#custom-tabs-one-profile-1" role="tab" aria-controls="custom-tabs-one-profile-1" aria-selected="false">Kết quả</a>
                          </li>
                          @endif
                        </ul>
                      </div>
                      <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade" id="custom-tabs-one-profile-1" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab-1">
                                <h2>{{$tender->title}}</h2>
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="bids-table" class="table table-bordered table-striped">
                                        <tr>
                                          <th>Nhà cung cấp</th>
                                          <th>Số lượng chọn</th>
                                          <th>Giá</th>
                                          <th>Thời gian giao</th>
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
                                        </tr>
                                        @endforeach
                                      </table>
                                    </div>
                                  </div>
                            </div>

                          <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <h2>{{$tender->title}}</h2>
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Thời gian bắt đầu</strong><br>
                                            {{$tender->tender_start_time}}<br>
                                          </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Thời gian kết thúc</strong><br>
                                            {!! $tender->tender_end_time !!}<br>
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
                                            @endforeach
                                          </address>
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

                                <!-- /.card-body -->
                            </div>
                        </div>
                          <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                            <h2>{{$tender->title}}</h2>
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                          <label class="control-label">Nhà thầu</label>
                                          <!-- checkbox -->
                                          <div class="form-group">
                                            @foreach($supplier_selected_statuses as $supplier_status)
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" onclick="return false;"
                                                name="supplier_ids[]" value="{{$supplier_status->supplier_id}}"
                                                @if(in_array($supplier_status->supplier_id, $selected_supplier_ids))
                                                    checked
                                                @endif
                                              >
                                              @if(in_array($supplier_status->supplier_id, $bided_supplier_ids))
                                                <i class="fas fa-envelope fa-sm" style="color:#007BFF;"></i>
                                              @else
                                                <i class="far fa-envelope fa-sm"></i>
                                              @endif
                                              @php
                                                  $supplier = App\Models\Supplier::findOrFail($supplier_status->supplier_id);
                                              @endphp
                                              <label class="form-check-label">{{$supplier->name}}</label>
                                              @if($supplier_status->reason)
                                                <label class="form-check-label" style="color:red;">
                                                    ({{$supplier_status->reason}})
                                                </label>
                                              @endif
                                            </div>
                                            @endforeach
                                          </div>
                                        </div>
                                    </div>

                                    @if($bids->count())
                                    <table id="bids-table" class="table table-bordered table-hover">
                                        <tr>
                                          <th>Trúng thầu</th>
                                          <th style="width: 11%;">Số lượng</th>
                                          <th style="width: 30%;">Nhà cung cấp</th>
                                          <th>Giá</th>
                                          <th style="width: 30%;">Điều kiện thanh toán</th>
                                          <th>Ghi chú</th>
                                        </tr>
                                        @foreach ($bids as $bid)
                                        <tr style="color:@if($bid->quantity_id % 2 == 0) #057ba9 @endif">
                                          <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" onclick="return false;"
                                                  @if($bid->is_selected)
                                                      checked
                                                  @endif
                                                >
                                              </div>
                                          </td>
                                          <td>{{$bid->quantity->quantity}} {{$bid->quantity->quantity_unit}}</td>
                                          <td>{{$bid->user->supplier->name}} ({{$bid->user->email}})</td>
                                          @if('VND' == $bid->price_unit)
                                          <td>{{ number_format($bid->price, 0, ',', ',') }} ({{$bid->price_unit}})</td>
                                          @else
                                          <td>{{ number_format($bid->price, 2, ',', ' ') }} ({{$bid->price_unit}})</td>
                                          @endif
                                          <td>{{ $bid->payment_condition }}</td>
                                          <td>{{ $bid->note }}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    @endif

                                    @if($proposes->count())
                                    <hr>
                                    <table id="bids-table" class="table table-bordered table-hover">
                                        <tr>
                                          <th>Đề xuất</th>
                                          <th style="width: 5%;">Xóa</th>
                                        </tr>
                                        @foreach ($proposes as $item)
                                        <tr>
                                          <td>{{ $item->propose }}</td>
                                          <td>
                                            <form action="{{route('admin.tenders.destroy.propose', $item->id)}}" method="POST">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" name="submit" class="btn btn-danger" onClick="return confirm('Bạn có chắc chắn muốn xóa?')"">
                                                <i class="fas fa-trash-alt"></i>
                                                </button>

                                                {{csrf_field()}}
                                            </form>
                                          </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    @endif
                                </div>
                                  <div class="card-footer clearfix">
                                    @if(Auth::user()->can('send-result')
                                    && Carbon\Carbon::now()->greaterThan($tender->tender_end_time)
                                    && $tender->status == 'In-progress')
                                      <a href="{{route('admin.tenders.result', $tender->id)}}">
                                          <button role="button" type="button" class="btn btn-success float-right"><i class="fas fa-check"></i> Chọn kết quả</button>
                                      </a>
                                    @endif

                                    @if(Auth::user()->can('create-propose')
                                    && Carbon\Carbon::now()->greaterThan($tender->tender_end_time)
                                    && $tender->status == 'In-progress')
                                      <button type="button" class="btn btn-success float-left" data-toggle="modal" data-target="#create_propose">
                                        <i class="fas fa-clipboard-check"></i> Đề xuất
                                      </button>
                                    @endif
                                  </div>
                              </div>
                        </div>
                        </div>
                      </div>
                      <!-- /.card -->
                    </div>
                  </div>
            </div>


            <form class="form-horizontal" method="post" action="{{ route('admin.tenders.create.propose', $tender->id) }}" name="store_propose" id="store_propose" novalidate="novalidate">
                {{ csrf_field() }}
                <div class="modal fade" id="create_propose">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="control-group">
                                            <label class="control-label">Đề xuất của bạn</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" name="propose" id="propose" required="">
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

            <div class="row">
                <div class="col-12">

                </div>
            </div>
        </div>
    </section>
@endsection
