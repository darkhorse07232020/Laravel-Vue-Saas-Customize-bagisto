@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.tenants.edit-title') }}
@endsection

@section('content')
    <seller-registration></seller-registration>

    @push('scripts')
        <script type="text/x-template" id ="seller-details-form">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/super/companies/tenants') }}';"></i>
        
                            {{ __('saas::app.super-user.tenants.edit-title') }}
                        </h1>
                    </div>
                </div>
        
                <div class="page-content">
                    <form method="POST" action="{{ route('super.tenants.update', $company->id) }}">
                        @csrf

                        <div class="control-group">
                            <label for="name" class="required">{{ __('saas::app.super-user.tenants.name') }}</label>

                            <input type="text" v-validate="'required'" class="control" v-model="name" placeholder="name" name="name" data-vv-as="&quot;{{ __('saas::app.super-user.tenants.name') }}&quot;">

                            <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                            <label for="email" class="required">{{ __('saas::app.super-user.tenants.email') }}</label>

                            <input type="text" class="control" name="email" v-model="email" placeholder="email" data-vv-as="&quot;{{ __('saas::app.super-user.tenants.email') }}&quot;">

                            <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('domain') ? 'has-error' : '']">
                            <label for="domain" class="required">{{ __('saas::app.super-user.tenants.domain') }}</label>

                            <input type="text" v-validate="'required'" class="control" name="domain" v-model="domain" placeholder="domain" data-vv-as="&quot;{{ __('saas::app.super-user.tenants.domain') }}&quot;">

                            <span class="control-error" v-if="errors.has('domain')">@{{ errors.first('domain') }}</span>
                        </div>
                        
                        <div class="control-group" :class="[errors.has('cname') ? 'has-error' : '']">
                            <label for="cname">{{ __('saas::app.super-user.tenants.cname') }}</label>

                            <input type="text" class="control" name="cname" v-model="cname" placeholder="cname" data-vv-as="&quot;{{ __('saas::app.super-user.tenants.cname') }}&quot;">

                            <span class="control-error" v-if="errors.has('cname')">@{{ errors.first('cname') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('is_active') ? 'has-error' : '']">
                            <label for="is_active" class="required">{{ __('saas::app.super-user.tenants.status') }}</label>
                            
                            <select class="control" name="is_active" v-model="is_active" v-validate="'required'" >
                                <option value="0">{{ __('saas::app.super-user.tenants.deactivate') }}</option>
                                <option value="1">{{ __('saas::app.super-user.tenants.activate') }}</option>
                            </select>

                            <span class="control-error" v-if="errors.has('is_active')">@{{ errors.first('is_active') }}</span>

                            @if ($company->is_active)
                                <span class="badge badge-md badge-success">
                                    {{ __('saas::app.super-user.tenants.activated') }}
                                </span>
                            @else
                                <span class="badge badge-md badge-danger">
                                    {{ __('saas::app.super-user.tenants.deactivated') }}
                                </span>
                            @endif
                        </div>

                        <button class="btn btn-lg btn-primary">
                            {{ __('saas::app.super-user.tenants.btn-update') }}
                        </button>
                    </form>
                </div>
            </div>
        </script>

        <script>
            Vue.component('seller-registration', {
                template: '#seller-details-form',
                inject: ['$validator'],

                data: () => ({
                    name: '{{ $company->name }}',
                    email: '{{ $company->email }}',
                    domain: '{{ $company->domain }}',
                    cname: '{{ $company->cname }}',
                    is_active: null
                }),

                mounted: function () {
                    @if ($company->is_active)
                        this.is_active = 1;
                    @else
                        this.is_active = 0;
                    @endif
                }
            });
        </script>
    @endpush
@endsection