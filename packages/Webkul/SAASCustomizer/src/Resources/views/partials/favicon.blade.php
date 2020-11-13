@if (isset($superChannel))
    <link rel="icon" sizes="16x16" href="{{ asset('storage/'.$superChannel->favicon) }}" />
@else
    <link rel="icon" sizes="16x16" href="{{ asset('vendor/webkul/ui/assets/images/favicon.ico') }}" />
@endif