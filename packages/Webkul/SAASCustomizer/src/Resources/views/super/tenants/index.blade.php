@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.tenants.title') }}
@stop

@section('content')
    <div class="content mt-50">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('saas::app.super-user.tenants.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            @inject('companies', 'Webkul\SAASCustomizer\DataGrids\TenantsDataGrid')
            {!! $companies->render() !!}
        </div>
    </div>
@stop