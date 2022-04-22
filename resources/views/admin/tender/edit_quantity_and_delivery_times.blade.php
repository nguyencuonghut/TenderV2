@section('title')
{{ 'Sửa số lượng và thời gian giao hàng' }}
@endsection

@extends('layouts.base')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sửa số lượng và thời gian giao hàng</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.tenders.index') }}">Tất cả tender</a></li>
              <li class="breadcrumb-item active">Sửa số lượng và thời gian giao hàng</li>
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
                    <form class="form-horizontal" method="post" action="{{ route('admin.tenders.updateQuantityAndDeliveryTimes') }}" name="update_quantity_and_delivery_times" id="update_quantity_and_delivery_times" novalidate="novalidate">
                        @method('PATCH')
                        {{ csrf_field() }}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered" id="dynamicTable">
                                <tr>
                                    <th style="width: 20%;">Số lượng</th>
                                    <th style="width: 12%;">Đơn vị</th>
                                    <th>Thời gian giao hàng</th>
                                    <th style="width: 14%;"><button type="button" name="add" id="add" class="btn btn-success">Thêm mới</button></th>
                                </tr>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($quantity_and_delivery_times as $item)
                                <tr>
                                    <input type="hidden" name="addmore[{{$i}}][tender_id]" value="{{$tender->id}}">
                                    <td><input type="number" name="addmore[{{$i}}][quantity]" placeholder="Số lượng" class="form-control" value="{{$item->quantity}}"/></td>
                                    <td>
                                        <select name="addmore[{{$i}}][quantity_unit]" class="form-control">
                                            <option value="tấn" {{ "tấn" == $item->quantity_unit ? 'selected' : '' }}>tấn</option>
                                            <option value="kg" {{ "kg" == $item->quantity_unit ? 'selected' : '' }}>kg</option>
                                            <option value="chiếc" {{ "chiếc" == $item->quantity_unit ? 'selected' : '' }}>chiếc</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="addmore[{{$i}}][delivery_time]" placeholder="Thời gian giao hàng" class="form-control" value="{{$item->delivery_time}}"/></td>
                                    <td><button type="button" class="btn btn-danger remove-tr">Remove</button></td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                                @endforeach
                            </table>
                            <br>

                            <button type="submit" class="btn btn-success">Lưu</button>
                        <div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script type="text/javascript">

    var i = 100;

    $("#add").click(function(){

        ++i;

        $("#dynamicTable").append('<tr><input type="hidden" name="addmore['+i+'][tender_id]" value="{{$tender->id}}"><td><input type="text" name="addmore['+i+'][quantity]" placeholder="Số lượng" class="form-control" /></td><td><select name="addmore['+i+'][quantity_unit]" class="form-control"><option value="tấn">tấn</option><option value="kg">kg</option><option value="chiếc">chiếc</option></select></td><td><input type="text" name="addmore['+i+'][delivery_time]" placeholder="Thời gian giao hàng" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
    });

    $(document).on('click', '.remove-tr', function(){
         $(this).parents('tr').remove();
    });

</script>
@endpush

