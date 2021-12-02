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
                        </ul>
                      </div>
                      <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                          <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
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
                                            <strong>Trạng thái</strong><br>
                                            {!! $tender->status !!}<br>
                                          </address>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Tên hàng</strong><br>
                                            {{$tender->material->name}}<br>
                                          </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                          <address>
                                            <strong>Số lượng và thời gian giao hàng</strong><br>
                                            {!! $tender->quantity_and_delivery_time !!}<br>
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
                                            {{$tender->payment_condition}}<br>
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
                                            {!!$tender->quality!!}<br>
                                          </address>
                                        </div>
                                    </div>
                                </div>

                                <!-- /.card-body -->
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
                            </div>
                        </div>
                          <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                  <table id="bids-table" class="table table-bordered table-striped">
                                    <tr>
                                      <th>STT</th>
                                      <th style="width: 30%;">Nhà cung cấp</th>
                                      <th>Số lượng</th>
                                      <th>Giá</th>
                                      <th>Thời gian chào</th>
                                      <th>Ghi chú</th>
                                    </tr>
                                    @php
                                        $i = 0
                                    @endphp
                                    @foreach ($bids as $bid)
                                    <tr>
                                      <td>{{++$i}}</td>
                                      <td>{{$bid->user->supplier->name}} ({{$bid->user->email}})</td>
                                      <td>{{ number_format($bid->quantity, 0, '.', ',') }} ({{$bid->quantity_unit}})</td>
                                      @if('VND' == $bid->price_unit)
                                      <td>{{ number_format($bid->price, 0, ',', ',') }} ({{$bid->price_unit}})</td>
                                      @else
                                      <td>{{ number_format($bid->price, 2, ',', ' ') }} ({{$bid->price_unit}})</td>
                                      @endif
                                      <td>{{ $bid->created_at }}</td>
                                      <td>{{ $bid->note }}</td>
                                    </tr>
                                    @endforeach
                                  </table>
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



            <div class="row">
                <div class="col-12">

                </div>
            </div>
        </div>
    </section>
@endsection
