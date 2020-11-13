<!DOCTYPE html>

<html lang="{{ config('app.locale') }}">
    <head>
        <title>@yield('page_title')</title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @if (company()->getSuperConfigData('general.design.super.logo_image'))
            <link rel="icon" sizes="16x16" href="{{ \Illuminate\Support\Facades\Storage::url(company()->getSuperConfigData('general.design.super.logo_image')) }}" />
        @else
            <link rel="icon" sizes="16x16" href="{{ asset('vendor/webkul/ui/assets/images/favicon.ico') }}" />
        @endif

        <link rel="stylesheet" href="{{ asset('vendor/webkul/admin/assets/css/admin.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/webkul/ui/assets/css/ui.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/webkul/saas/assets/css/saas.css') }}">

        @yield('head')

        @yield('css')

        {!! view_render_event('bagisto.saas.layout.head') !!}
    </head>

    <body class="super-section" @if (app()->getLocale() == 'ar') class="rtl" @endif style="scroll-behavior: smooth;">
        {!! view_render_event('bagisto.saas.body.before') !!}

        <div id="app">
            {!! view_render_event('bagisto.saas.body.context.before') !!}

            <flash-wrapper ref='flashes'></flash-wrapper>

            <div class="container super-admin-login">
                @yield('content-wrapper')
            </div>

            {!! view_render_event('bagisto.saas.body.context.after') !!}
        </div>

        <script type="text/javascript">
            window.flashMessages = [];

            @if ($success = session('success'))
                window.flashMessages = [{'type': 'alert-success', 'message': "{{ $success }}" }];
            @elseif ($warning = session('warning'))
                window.flashMessages = [{'type': 'alert-warning', 'message': "{{ $warning }}" }];
            @elseif ($error = session('error'))
                window.flashMessages = [{'type': 'alert-error', 'message': "{{ $error }}" }];
            @elseif ($info = session('info'))
                window.flashMessages = [{'type': 'alert-error', 'message': "{{ $info }}" }];
            @endif

            window.serverErrors = [];

            @if (isset($errors))
                @if (count($errors))
                    window.serverErrors = @json($errors->getMessages());
                @endif
            @endif
        </script>

        <script type="text/javascript" src="{{ asset('vendor/webkul/admin/assets/js/admin.js') }}"></script>
        <script type="text/javascript" src="{{ asset('vendor/webkul/ui/assets/js/ui.js') }}"></script>

        @stack('scripts')

        <div class="modal-overlay"></div>

        {!! view_render_event('bagisto.saas.body.after') !!}
    </body>
</html>