<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@elseif(isset($user_name))
# {!! ___('emails.globals.greeting', ['user_name' => $user_name]) !!}
@else
@lang('Hello')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
<br/>
{{ $salutation }}
@else
<br/>
{!! ___('emails.globals.salutation', ['app_name' => config('app.name')]) !!}
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
{!! ___('emails.globals.button_fallback_text', ['action_text' => $actionText, 'display_url' => $displayableActionUrl, 'action_url' => $actionUrl]) !!}
</x-slot:subcopy>
@endisset
</x-mail::message>
