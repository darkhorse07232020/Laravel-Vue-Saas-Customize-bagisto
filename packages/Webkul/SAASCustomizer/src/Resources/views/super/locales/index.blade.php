@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.settings.locales.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('saas::app.super-user.settings.locales.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('super.locales.create') }}" class="btn btn-lg btn-primary">
                    {{ __('saas::app.super-user.settings.locales.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">

            @inject('locales','Webkul\SAASCustomizer\DataGrids\LocalesDataGrid')
            {!! $locales->render() !!}
        </div>
    </div>
@stop