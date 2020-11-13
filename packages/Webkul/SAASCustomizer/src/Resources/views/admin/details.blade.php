@extends('admin::layouts.content')

@section('page_title')
    {{ __('saas::app.admin.tenant.company') }} {{ __('saas::app.admin.tenant.profile') }}
@stop

@section('content')
<div class="content">
    {!! view_render_event('saas.company.profile.update.before') !!}

    <form method="post" action="{{ route('company.profile.update') }}" @submit.prevent="onSubmit">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                    {{ __('saas::app.admin.tenant.company') }} {{ __('saas::app.admin.tenant.profile') }}
                </h1>
            </div>

            <div class="page-action">
                <button type="submit" class="btn btn-lg btn-primary">
                    {{ __('saas::app.admin.tenant.update') }} {{ __('saas::app.admin.tenant.profile') }}
                </button>
            </div>
        </div>

        @csrf

        {!! view_render_event('saas.company.profile.create_form_controls.before') !!}

        <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
            <label for="first_name" class="required">{{ __('saas::app.admin.tenant.first-name') }}</label>

            <input type="text" class="control" name="first_name" v-validate="'required'" data-vv-as="&quot;{{ __('saas::app.admin.tenant.first-name') }}&quot;" value="{{ isset($details) ? $details->first_name : old('first_name)') }}">

            <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
            <label for="last_name" class="required">{{ __('saas::app.admin.tenant.last-name') }}</label>

            <input type="text" class="control" name="last_name" v-validate="'required'" data-vv-as="&quot;{{ __('saas::app.admin.tenant.last-name') }}&quot;" value="{{ isset($details) ? $details->last_name : old('last_name)') }}">

            <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
            <label for="email" class="required">{{ __('saas::app.admin.tenant.email') }}</label>

            <input type="text" class="control" name="email" v-validate="'required|email'" data-vv-as="&quot;{{ __('saas::app.admin.tenant.email') }}&quot;" value="{{ isset($details) ? $details->email : old('email)') }}">

            <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('skype') ? 'has-error' : '']">
            <label for="skype">{{ __('saas::app.admin.tenant.skype') }}</label>

            <input type="text" class="control" name="skype" v-validate="'min:6|max:32'" data-vv-as="&quot;{{ __('saas::app.admin.tenant.skype') }}&quot;" value="{{ isset($details) ? $details->skype : old('skype)') }}">

            <span class="control-error" v-if="errors.has('skype')">@{{ errors.first('skype') }}</span>
        </div>
        
        <div class="control-group" :class="[errors.has('cname') ? 'has-error' : '']">
            <label for="cname">{{ __('saas::app.admin.tenant.c-name') }}</label>

            <input type="text" class="control" name="cname" data-vv-as="&quot;{{ __('saas::app.admin.tenant.c-name') }}&quot;" value="{{ isset($company->cname) ? $company->cname : old('cname)') }}">

            <span class="control-error" v-if="errors.has('cname')">@{{ errors.first('cname') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
            <label for="phone" class="required">{{ __('shop::app.customer.account.address.create.phone') }}</label>

            <input type="text" class="control" name="phone" v-validate="'required'" data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.phone') }}&quot;" value="{{ isset($details) ? $details->phone : old('phone)') }}">

            <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
        </div>

        {!! view_render_event('saas.company.profile.create_form_controls.after') !!}
    </form>

    {!! view_render_event('saas.company.profile.update.after') !!}
</div>
@endsection