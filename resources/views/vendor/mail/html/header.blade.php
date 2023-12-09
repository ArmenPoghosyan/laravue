@props(['url'])
<tr>
<td class="header">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('images/logo/logo.png') }}" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</td>
</tr>
