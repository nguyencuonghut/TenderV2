@section('title')
{{ 'Sửa số lượng và thời gian giao hàng' }}
@endsection

@push('styles')
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

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
                                    <th class="required-field" style="width: 30%;">Tên hàng</th>
                                    <th style="width: 15%;">Số lượng</th>
                                    <th style="width: 12%;">Đơn vị</th>
                                    <th>Thời gian giao hàng</th>
                                    <th style="width: 14%;"><button type="button" name="add" id="add" class="btn btn-success">Thêm</button></th>
                                </tr>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($quantity_and_delivery_times as $item)
                                <tr>
                                    <input type="hidden" name="addmore[{{$i}}][tender_id]" value="{{$tender->id}}">
                                    <td>
                                        <select name="addmore[{{$i}}][material_id]" class="form-control select2" style="width: 100%;">
                                            <option selected="selected" disabled>Chọn tên hàng</option>
                                            @foreach($materials as $key => $value)
                                                <option value="{{$key}}" {{ $key == $item->material_id ? 'selected' : '' }}>{{\Illuminate\Support\Str::limit($value, 30);}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="addmore[{{$i}}][quantity]" placeholder="Số lượng" class="form-control" value="{{$item->quantity}}"/></td>
                                    <td>
                                        <select name="addmore[{{$i}}][quantity_unit]" class="form-control">
                                            <option value="tấn" {{ "tấn" == $item->quantity_unit ? 'selected' : '' }}>tấn</option>
                                            <option value="kg" {{ "kg" == $item->quantity_unit ? 'selected' : '' }}>kg</option>
                                            <option value="chiếc" {{ "chiếc" == $item->quantity_unit ? 'selected' : '' }}>chiếc</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="addmore[{{$i}}][delivery_time]" placeholder="Thời gian giao hàng" class="form-control" value="{{$item->delivery_time}}"/></td>
                                    <td><button type="button" class="btn btn-danger remove-tr">Xóa</button></td>
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
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script type="text/javascript">
    //Initialize Select2 Elements
    $('.select2').select2({
    theme: 'bootstrap4'
    })

    var i = 100;

    $("#add").click(function(){

        ++i;

        $("#dynamicTable").append('<tr><input type="hidden" name="addmore['+i+'][tender_id]" value="{{$tender->id}}"><td><select name="addmore['+i+'][material_id]" class="form-control select2" style="width: 100%;"><option selected="selected" disabled>Chọn tên hàng</option>@foreach($materials as $key => $value)<option value="{{$key}}">{{\Illuminate\Support\Str::limit($value, 30);}}</option>@endforeach</select></td><td><input type="text" name="addmore['+i+'][quantity]" placeholder="Số lượng" class="form-control" /></td><td><select name="addmore['+i+'][quantity_unit]" class="form-control"><option value="tấn">tấn</option><option value="kg">kg</option><option value="chiếc">chiếc</option></select></td><td><input type="text" name="addmore['+i+'][delivery_time]" placeholder="Thời gian giao hàng" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Xóa</button></td></tr>');
        //Reinitialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        })
    });

    $(document).on('click', '.remove-tr', function(){
         $(this).parents('tr').remove();
    });

</script>
@endpush

