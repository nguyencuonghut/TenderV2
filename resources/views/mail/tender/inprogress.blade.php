@component('mail::message', ['tender' => $tender])
Xin chào,

Xin mời quý nhà cung cấp chào thầu cho: {{$tender->title}}.
<br>
- Thời gian bắt đầu: {{date('d/m/Y H:i', strtotime($tender->tender_in_progress_time))}}.
<br>
- Thời gian kết thúc: {{date('d/m/Y H:i', strtotime($tender->tender_end_time))}}.
<br>
Lưu ý: quý nhà cung cấp chỉ được chào thầu trong thời gian từ {{date('H:i', strtotime($tender->tender_in_progress_time))}} đến {{date('H:i', strtotime($tender->tender_end_time))}}.

@component('mail::table',['quantity_and_delivery_times' => $quantity_and_delivery_times])
| Tên hàng      | Số lượng      | Thời gian giao  |
|:-------------:|:-------------:| ---------------:|
@foreach($quantity_and_delivery_times as $item)
| {{\Illuminate\Support\Str::limit($item->material->name, 30)}}|  {{number_format($item->quantity, 0, '.', ',')}} {{$item->quantity_unit}}|        {{$item->delivery_time}} |
@endforeach
@endcomponent

@component('mail::button', ['url' => $url])
Mở tender
@endcomponent

Xin cảm ơn,<br>
{{ config('app.name') }}
@endcomponent
