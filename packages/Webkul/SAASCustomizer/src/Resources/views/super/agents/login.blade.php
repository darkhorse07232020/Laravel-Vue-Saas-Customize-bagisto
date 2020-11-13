@extends('saas::super.layouts.anonymous-master')

@section('page_title')
    {{ __('saas::app.super-user.settings.agents.sign-in.title') }}
@stop

@section('content-wrapper')
    <div class="form-container">
        <div class="row logo">
            <a href="{{ route('super.tenants.index') }}">
                @if (company()->getSuperConfigData('general.design.super.logo_image'))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(company()->getSuperConfigData('general.design.super.logo_image')) }}" alt="{{ config('app.name') }}" style="height: 50px; width: 110px;"/>
                @else
                    <img src="{{ asset('vendor/webkul/ui/assets/images/logo.png') }}" alt="{{ config('app.name') }}"/>
                @endif
            </a>
        </div>

        <h1>{{ __('saas::app.super-user.settings.agents.sign-in.title') }}</h1>

        <form class="registration" method="POST" action="{{ route('super.session.create') }}" @submit.prevent="onSubmit">
            @csrf
            <div class="sign-in">
                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="email" class="required">{{ __('saas::app.super-user.settings.agents.sign-in.email') }}</label>
                    <input type="text" v-validate="'required'" class="control" id="email" name="email" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.sign-in.email') }}&quot;"/>
                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <label for="password" class="required">{{ __('saas::app.super-user.settings.agents.sign-in.password') }}</label>
                    <input type="password" v-validate="'required|min:6'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.sign-in.password') }}&quot;"/>
                    <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                </div>

                <div class="control-group">
                    <a href="{{ route('super.forget-password.create') }}">{{ __('saas::app.super-user.settings.agents.sign-in.forget-password-link-title') }}</a>
                </div>

                <div class="button-group">
                    <button class="btn btn-xl btn-primary">{{ __('saas::app.super-user.settings.agents.sign-in.btn-submit') }}</button>
                </div>
            </div>
        </form>
    </div>
@stop