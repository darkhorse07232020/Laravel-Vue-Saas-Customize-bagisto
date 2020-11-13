@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.settings.exchange-rates.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('saas::app.super-user.settings.exchange-rates.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('super.exchange_rates.create') }}" class="btn btn-lg btn-primary">
                    {{ __('saas::app.super-user.settings.exchange-rates.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('exchange_rates','Webkul\SAASCustomizer\DataGrids\ExchangeRatesDataGrid')
            {!! $exchange_rates->render() !!}
        </div>
    </div>
@stop