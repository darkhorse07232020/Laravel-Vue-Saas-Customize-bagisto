@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.tenants.view-title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/super/companies/tenants') }}';"></i>

                    {{ __('saas::app.super-user.tenants.view-title') }}
                </h1>
            </div>
        </div>

        <div class="page-content">
            <div class="table">
                <table>
                    <thead>
                        <tr style="font-weight: bold">
                            <td>{{ __('saas::app.super-user.tenants.no-of-products') }}</td>
                            <td>{{ __('saas::app.super-user.tenants.no-of-attributes') }}</td>
                            <td>{{ __('saas::app.super-user.tenants.no-of-customers') }}</td>
                            <td>{{ __('saas::app.super-user.tenants.no-of-customer-groups') }}</td>
                            <td>{{ __('saas::app.super-user.tenants.no-of-categories') }}</td>
                            <td>{{ __('saas::app.super-user.tenants.mapped-domain') }}</td>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>{{ $company[1]['products'] }}</td>
                            <td>{{ $company[1]['attributes'] }}</td>
                            <td>{{ $company[1]['customers'] }}</td>
                            <td>{{ $company[1]['customer-groups'] }}</td>
                            <td>{{ $company[1]['categories'] }}</td>
                            <td>{{ $company[0]->domain }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop