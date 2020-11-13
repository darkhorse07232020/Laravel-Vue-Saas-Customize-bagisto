@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.settings.currencies.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('saas::app.super-user.settings.currencies.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('super.currencies.create') }}" class="btn btn-lg btn-primary">
                    {{ __('saas::app.super-user.settings.currencies.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('currencies','Webkul\SAASCustomizer\DataGrids\CurrencyDataGrid')
            {!! $currencies->render() !!}
        </div>
    </div>
@stop