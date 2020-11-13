@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.settings.channels.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('saas::app.super-user.settings.channels.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            @inject('channels','Webkul\SAASCustomizer\DataGrids\ChannelDataGrid')
            {!! $channels->render() !!}
        </div>
    </div>
@stop