<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://honghafeed.com.vn/Uploads/images/icon/logo%20hong%20ha-01.png" class="logo" alt="Honghafeed Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
