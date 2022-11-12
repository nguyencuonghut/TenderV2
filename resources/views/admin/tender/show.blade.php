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
                          @if ($selected_bids->count()
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
                                <h2>{{$tender->title}}</h2>
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                      <table id="bids-table" class="table table-bordered table-striped">
                                        <tr>
                                          <th>Nhà cung cấp</th>
                                          <th>Lượng trúng</th>
                                          <th>Giá</th>
                                          <th>Thời gian giao</th>
                                        </tr>
                                        @foreach ($selected_bids as $bid)
                                        <tr>
                                          <td style="width:40%;">{{$bid->user->supplier->name}} ({{ $bid->user->email }})</td>
                                          <td>{{ number_format($bid->tender_quantity, 0, '.', ',') }} {{$bid->tender_quantity_unit}}</td>
                                          @if('đồng/kg' == $bid->price_unit
                                              || 'đồng/chiếc' == $bid->price_unit)
                                          <td>{{ $bid->price }} ({{$bid->price_unit}})</td>
                                          @else
                                          <td>{{ $bid->price }} ({{$bid->price_unit}})</td>
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
                                            <strong>Thời gian mở thầu</strong><br>
                                            {{date('d/m/Y H:i', strtotime($tender->tender_in_progress_time))}}<br>
                                          </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Thời gian đóng thầu</strong><br>
                                            {{date('d/m/Y H:i', strtotime($tender->tender_end_time)) }}<br>
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
                                    <!-- TODO: Only show the first material's required quality: -->
                                    <hr>
                                    <div class="row invoice-info">
                                        <div class="col-sm-12 invoice-col">
                                            <address>
                                                <strong>Yêu cầu chất lượng</strong><br>
                                                {!!$tender->quantity_and_delivery_times->first()->material->quality!!}<br>
                                            </address>
                                        </div>
                                    </div>

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

                                <!-- /.card-body -->
                            </div>
                        </div>
                          <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                            <h2>{{$tender->title}}</h2>
                            <h6>(Thời gian thầu: {{date('d/m/Y H:i', strtotime($tender->tender_in_progress_time))}} - {{date('d/m/Y H:i', strtotime($tender->tender_end_time))}})</h4>
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
                                              @if(in_array($supplier_status->supplier_id, $selected_bided_supplier_ids))
                                                <i class="fas fa-certificate fa-sm" style="color:#007BFF;"></i>
                                                @else
                                                <i class="far fa-certificate fa-sm"></i>
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
                                </div>
                                  <div class="card-footer clearfix">
                                    @if(Auth::user()->can('create-propose')
                                    && Carbon\Carbon::now()->greaterThan($tender->tender_end_time)
                                    && $tender->status == 'Đang diễn ra'
                                    && $bids->count())
                                      <button type="button" class="btn btn-success float-left" data-toggle="modal" data-target="#create_propose">
                                        <i class="fas fa-clipboard-check"></i> Đề xuất
                                      </button>
                                    @endif

                                    @if(Auth::user()->can('create-result')
                                    && Carbon\Carbon::now()->greaterThan($tender->tender_end_time)
                                    && $tender->status == 'Đang diễn ra'
                                    && $bids->count())
                                    <a href="{{route('admin.tenders.createResult', $tender->id)}}">
                                        <button role="button" type="button" class="btn btn-success float-right"><i class="fas fa-check"></i> Chọn kết quả</button>
                                    </a>
                                    @endif
                                  </div>
                              </div>



                            @if($bids->count())
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h5 class="card-title">Báo cáo thầu</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            @php
                                                $i = 0;
                                            @endphp
                                            <table id="bids-table" class="table table-bordered">
                                                <tr>
                                                  <th>STT</th>
                                                  <th>Mã hàng</th>
                                                  <th>Tên hàng</th>
                                                  <th>Số lượng</th>
                                                  <th>Thời gian giao</th>
                                                  @foreach ($unique_bided_supplier_ids as $item)
                                                  @php
                                                      $supplier = App\Models\Supplier::findOrFail($item);
                                                  @endphp
                                                  <th>{{$supplier->name}}</th>
                                                  @endforeach
                                                </tr>
                                                @foreach ($tender->quantity_and_delivery_times as $quantity_and_delivery_time)
                                                <tr>
                                                  <td>{{++$i}}</td>
                                                  <td>{{$quantity_and_delivery_time->material->code}}</td>
                                                  <td>{{$quantity_and_delivery_time->material->name}}</td>
                                                  <td>{{number_format($quantity_and_delivery_time->quantity, 0, '.', ',')}} {{$quantity_and_delivery_time->quantity_unit}}</td>
                                                  <td>{{$quantity_and_delivery_time->delivery_time}}</td>

                                                  @foreach ($unique_bided_supplier_ids as $item)
                                                  @php
                                                      $supplier = App\Models\Supplier::findOrFail($item);
                                                      $current_bid = App\Models\Bid::where('tender_id', $tender->id)->where('quantity_id', $quantity_and_delivery_time->id)->where('supplier_id', $item)->first();
                                                  @endphp
                                                  @if($current_bid != null)
                                                  <td>
                                                      - Giá: {{$current_bid->price}} ({{$current_bid->price_unit}}) <br>
                                                      - Lượng chào: {{number_format($current_bid->bid_quantity, 0, '.', ',')}} {{$current_bid->bid_quantity_unit}} <br>
                                                      @if($current_bid->seller)
                                                      - Bên bán: {{$current_bid->seller}}
                                                      @endif
                                                  </td>
                                                  @else
                                                  <td></td>
                                                  @endif
                                                  @endforeach
                                                </tr>
                                                @endforeach
                                                <tr>
                                                  <td colspan="5"><b>Điều kiện thanh toán</b></td>
                                                  @foreach ($unique_bided_supplier_ids as $item)
                                                  @php
                                                      $supplier = App\Models\Supplier::findOrFail($item);
                                                      $current_bid = App\Models\Bid::where('tender_id', $tender->id)->where('supplier_id', $item)->first();
                                                  @endphp
                                                  @if($current_bid != null)
                                                  <td>{{$tender->payment_condition}}</td>
                                                  @else
                                                  <td></td>
                                                  @endif
                                                  @endforeach
                                                </tr>

                                                <tr>
                                                  <td colspan="5"><b>Xuất xứ</b></td>
                                                  @foreach ($unique_bided_supplier_ids as $item)
                                                  <!--
                                                  @php
                                                      $supplier = App\Models\Supplier::findOrFail($item);
                                                      $current_bids = App\Models\Bid::where('tender_id', $tender->id)->where('supplier_id', $item)->get();
                                                      $origin = '';
                                                      foreach($current_bids as $bid){
                                                        if($origin != $bid->origin){
                                                          if($origin != ''){
                                                            $origin = $origin . ', ' . $bid->origin;
                                                          }else{
                                                            $origin = $origin . $bid->origin;
                                                          }
                                                        }
                                                      }
                                                  @endphp
                                                  -->
                                                  <td>{{$tender->origin}}</td>
                                                  @endforeach
                                                </tr>
                                                @if($selected_bids->count())
                                                <tr>
                                                    <td colspan="5"><b>Kết quả</b></td>
                                                    @foreach ($unique_bided_supplier_ids as $item)
                                                    @php
                                                        $supplier = App\Models\Supplier::findOrFail($item);
                                                        $current_bids = App\Models\Bid::where('tender_id', $tender->id)->where('supplier_id', $item)->get();
                                                        $result = '';
                                                        foreach($current_bids as $bid){
                                                          if($bid->is_selected){
                                                              if($result == ''){
                                                                $result = $result . 'Trúng thầu: <br>' . '- ' . number_format($bid->tender_quantity, 0, '.', ',') . ' ' . $bid->tender_quantity_unit . ' ' . $bid->quantity->material->name;
                                                              }else{
                                                                $result = $result . '<br>' . '- ' . number_format($bid->tender_quantity, 0, '.', ',') . ' ' . $bid->tender_quantity_unit . ' ' . $bid->quantity->material->name;
                                                              }
                                                          }
                                                        }
                                                    @endphp
                                                    <td>{!! $result !!}</td>
                                                    @endforeach
                                                </tr>
                                                @endif
                                                @if($propose)
                                                <tr>
                                                    <td colspan="5"><b>Đề xuất</b></td>
                                                    <td colspan="{{sizeof($unique_bided_supplier_ids)}}">{!! $propose->propose !!}</td>
                                                </tr>
                                                @endif
                                                @if($tender->approve_result)
                                                <tr>
                                                    <td colspan="5"><b>Phê duyệt</b> (Mr.{{$tender->manager->name}})</td>
                                                    @php
                                                        $approve_result = '';
                                                        if('Đồng ý' == $tender->approve_result){
                                                            $approve_result = $approve_result . '<span class="badge bg-success">' . $tender->approve_result . '</span>';
                                                        }elseif('Từ chối' == $tender->approve_result){
                                                            $approve_result = $approve_result . '<span class="badge bg-danger">' . $tender->approve_result . '</span>' . '<br>';
                                                            $approve_result = $approve_result . 'Bình luận:' . '<br>';

                                                            $comment = App\Models\TenderApproveComment::where('tender_id', $tender->id)->latest()->first();
                                                            $approve_result = $approve_result . $comment->comment;
                                                        }
                                                    @endphp
                                                    <td colspan="{{sizeof($unique_bided_supplier_ids)}}">{!! $approve_result !!}</td>
                                                </tr>
                                                @endif
                                              </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(Auth::user()->can('create-result')
                            && Carbon\Carbon::now()->greaterThan($tender->tender_end_time)
                            && $tender->status == 'Đang diễn ra'
                            && 0 != $selected_bids->count())
                            <a href="{{route('admin.tenders.createRequestApprove', $tender->id)}}">
                                <button role="button" type="button" class="btn btn-success"> Yêu cầu duyệt</button>
                            </a>
                            <br>
                            @endif

                            @if(Auth::user()->can('approve-result')
                            && Carbon\Carbon::now()->greaterThan($tender->tender_end_time)
                            && $tender->status == 'Đang diễn ra'
                            && 0 != $selected_bids->count())
                            <br>
                            <a href="{{route('admin.tenders.getApproveResult', $tender->id)}}">
                                <button role="button" type="button" class="btn btn-success"><i class="fas fa-check"></i> Duyệt kết quả</button>
                            </a>
                            @endif

                            @endif
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
                                                <input type="text" class="form-control" name="propose" id="propose" required="" @if (null != $propose) value="{{$propose->propose}}" @endif>
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
</div>
@endsection
