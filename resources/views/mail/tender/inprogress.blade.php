@component('mail::message', ['tender' => $tender])
Xin chào,

Xin mời quý nhà cung cấp chào thầu cho: {{$tender->title}}

@component('mail::table',['quantity_and_delivery_times' => $quantity_and_delivery_times])
| Tên hàng      | Số lượng      | Thời gian giao  |
|:-------------:|:-------------:| ---------------:|
@foreach($quantity_and_delivery_times as $item)
| {{\Illuminate\Support\Str::limit($item->material->name, 30)}}|  {{$item->quantity}} {{$item->quantity_unit}}|        {{$item->delivery_time}} |
@endforeach
@endcomponent

@component('mail::button', ['url' => $url])
Mở tender
@endcomponent

Xin cảm ơn,<br>
{{ config('app.name') }}
@endcomponent
