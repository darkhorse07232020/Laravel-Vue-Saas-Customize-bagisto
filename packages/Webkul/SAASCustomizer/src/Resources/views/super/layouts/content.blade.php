@extends('saas::super.layouts.master')

@section('content-wrapper')
    <div class="inner-section">

        @include ('saas::super.layouts.nav-aside')

        <div class="content-wrapper">

            @if(auth()->guard('super-admin')->check() && ! request()->is('company/*'))
                @include ('saas::super.layouts.tabs')
            @endif

            @yield('content')

        </div>

    </div>
@stop