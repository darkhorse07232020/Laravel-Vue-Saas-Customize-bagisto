@extends('admin::layouts.content')

@section('page_title')
{{ __('saas::app.admin.tenant.company-address.add-address-title') }}
@stop

@section('content')
<div class="content">
    {!! view_render_event('saas.company.address.create.before') !!}

    <form method="post" action="{{ route('company.address.create') }}" @submit.prevent="onSubmit">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                    {{ __('saas::app.admin.tenant.company-address.add-address-title') }}
                </h1>
            </div>

            <div class="page-action">
                <button type="submit" class="btn btn-lg btn-primary">
                     {{ __('saas::app.admin.tenant.company-address.save-btn-title') }}
                </button>
            </div>
        </div>

        @csrf

        {!! view_render_event('saas.address.create_form_controls.before') !!}

        <div class="control-group" :class="[errors.has('address1') ? 'has-error' : '']">
            <label for="address1" class="required">{{ __('saas::app.admin.tenant.address1') }}</label>

            <input type="text" class="control" name="address1" v-validate="'required'" data-vv-as="&quot;{{ __('saas::app.admin.tenant.address1') }}&quot;" value="{{ old('address1')}}" />

            <span class="control-error" v-if="errors.has('address1')">@{{ errors.first('address1') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('address2') ? 'has-error' : '']">
            <label for="address2">{{ __('saas::app.admin.tenant.address2') }}</label>
            <input type="text" class="control" name="address2" data-vv-as="&quot;{{ __('saas::app.admin.tenant.address2') }}&quot;" value="{{ old('address2')}}" >
            <span class="control-error" v-if="errors.has('address2')">@{{ errors.first('address2') }}</span>
        </div>

        @include ('shop::customers.account.address.country-state', ['countryCode' => old('country'), 'stateCode' => old('state')])

        <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
            <label for="city" class="required">{{ __('shop::app.customer.account.address.create.city') }}</label>
            <input type="text" class="control" name="city" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.city') }}&quot;" value="{{ old('city')}}" >
            <span class="control-error" v-if="errors.has('city')">@{{ errors.first('city') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('zip_code') ? 'has-error' : '']">
            <label for="zip_code" class="required">{{ __('shop::app.customer.account.address.create.postcode') }}</label>
            <input type="text" class="control" name="zip_code" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.postcode') }}&quot;" value="{{ old('zip_code')}}" />
            <span class="control-error" v-if="errors.has('zip_code')">@{{ errors.first('zip_code') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
            <label for="phone" class="required">{{ __('shop::app.customer.account.address.create.phone') }}</label>
            <input type="text" class="control" name="phone" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.phone') }}&quot;">
            <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
        </div>

        {!! view_render_event('saas.address.create_form_controls.after') !!}
    </form>

    {!! view_render_event('saas.company.address.create.after') !!}
</div>
@endsection