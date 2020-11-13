@php
    $superChannel = app('Webkul\SAASCustomizer\Repositories\Super\ChannelRepository')->first();
@endphp

@if (isset($superChannel))
    @if (isset($superChannel->meta_title))
    <meta title="title" content="{{ $superChannel->meta_title }}" />
    @endif

    @if (isset($superChannel->meta_keywords))
        <meta title="keywords" content="{{ $superChannel->meta_keywords }}" />
    @endif

    @if (isset($superChannel->meta_description))
        <meta title="description" content="{{ $superChannel->meta_description }}" />
    @endif
@endif