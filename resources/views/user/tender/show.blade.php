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
                <div class="col-12">
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
                        <div class="card-footer clearfix">
                            <a href="{{route('user.tenders.bid', $tender->id)}}">
                                <button role="button" type="button" class="btn btn-success float-right"><i class="fas fa-gavel"></i> Đấu thầu</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
