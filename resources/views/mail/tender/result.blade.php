@component('mail::message', ['tender' => $tender])
Xin chào,

Công ty cổ phần dinh dưỡng Hồng Hà xin thông báo kết quả đấu thầu: {{$tender->title}}

@component('mail::table',['selected_bids' => $selected_bids])
| Số lượng      | Giá           | Thời gian giao  |
|:-------------:|:-------------:| --------:|
@foreach($selected_bids as $bid)
| {{$bid->tender_quantity}} {{$bid->tender_quantity_unit}}     | {{$bid->price}} ({{$bid->price_unit}}) |        {{$bid->delivery_time}} |
@endforeach
@endcomponent

@component('mail::button', ['url' => $url])
Mở tender
@endcomponent

Xin cảm ơn,<br>
{{ config('app.name') }}
@endcomponent
