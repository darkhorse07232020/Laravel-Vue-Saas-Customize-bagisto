@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.tenants.customers.title') }}
@stop

@section('content')
    <div class="content mt-50">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('saas::app.super-user.tenants.customers.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            @inject('tenant_customers', 'Webkul\SAASCustomizer\DataGrids\TenantCustomersDataGrid')
            {!! $tenant_customers->render() !!}
        </div>
    </div>
@stop