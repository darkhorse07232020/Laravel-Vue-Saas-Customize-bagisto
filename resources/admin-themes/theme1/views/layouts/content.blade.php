@extends('admin::layouts.master')

@section('content-wrapper')
    <div class="inner-section">
    
        @include ('admin::layouts.nav-aside')

        <div class="d-flex flex-column flex-row-fluid wrapper">

            @include ('admin::layouts.tabs')

            @yield('content')

        </div>
        
    </div>
@stop