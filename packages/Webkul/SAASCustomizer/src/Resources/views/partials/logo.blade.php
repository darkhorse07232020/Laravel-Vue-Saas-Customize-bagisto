<a href="{{ route('company.create.index') }}">
    @if (isset($superChannel))
        <img src="{{ asset('storage/'.$superChannel->logo) }}" alt="{{ $superChannel->title }}" style="max-height: {{ $height }};" />
    @else
        <img src="{{ asset('vendor/webkul/ui/assets/images/logo.png') }}" alt="{{ $alt }}"/>
    @endif
</a>