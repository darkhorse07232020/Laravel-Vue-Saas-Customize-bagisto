@extends('saas::super.layouts.anonymous-master')

@section('page_title')
    {{ __('saas::app.super-user.settings.agents.sign-in.reset-password.title') }}
@stop

@section('css')
    <style>
        .button-group {
            margin-bottom: 25px;
        }
        .primary-back-icon {
            vertical-align: middle;
        }
        .control-group .control {
            width: 100%;
        }
    </style>
@stop

@section('content-wrapper')

    <div class="form-container">
        <div class="row mb-30">
            {{ __('saas::app.super-user.settings.agents.sign-in.reset-password.title') }}
        </div>

        <form method="POST" action="{{ route('super.reset-password.store') }}" @submit.prevent="onSubmit">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <div class="reset-password">
                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="email" class="required">{{ __('saas::app.super-user.settings.agents.sign-in.reset-password.email') }}</label>
                    <input type="text" v-validate="'required|email'" class="control" id="email" name="email" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.sign-in.reset-password.email') }}&quot;" value="{{ old('email') }}"/>
                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <label for="password" class="required">{{ __('saas::app.super-user.settings.agents.sign-in.reset-password.password') }}</label>
                    <input type="password" v-validate="'required|min:6'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.sign-in.reset-password.password') }}&quot;"/>
                    <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                    <label for="password_confirmation" class="required">{{ __('saas::app.super-user.settings.agents.sign-in.reset-password.confirm-password') }}</label>
                    <input type="password" v-validate="'required|min:6|confirmed:password'" class="control" id="password_confirmation" name="password_confirmation" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.sign-in.reset-password.confirm-password') }}&quot;" data-vv-as="password"/>
                    <span class="control-error" v-if="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-xl btn-primary">{{ __('saas::app.super-user.settings.agents.sign-in.reset-password.submit-btn-title') }}</button>
                </div>

                <div class="control-group" style="margin-bottom: 0">
                    <a href="{{ route('super.session.create') }}">
                        <i class="icon primary-back-icon"></i>
                        {{ __('saas::app.super-user.settings.agents.sign-in.reset-password.back-link-title') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
@stop