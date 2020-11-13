@extends('saas::super.layouts.anonymous-master')

@section('page_title')
    {{ __('saas::app.super-user.settings.agents.sign-in.forget-password.title') }}
@stop

@section('css')
    <style>
        .button-group {
            margin-bottom: 25px;
        }
        .primary-back-icon {
            vertical-align: middle;
        }
    </style>
@stop

@section('content-wrapper')

    <div class="form-container">
        <div class="row mb-30">
            <h1>{{ __('saas::app.super-user.settings.agents.sign-in.forget-password.header-title') }}</h1>
        </div>

        <form method="POST" action="{{ route('super.forget-password.store') }}" @submit.prevent="onSubmit">
            @csrf
            <div class="forgot-password">
                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <label for="email" class="required">{{ __('saas::app.super-user.settings.agents.sign-in.forget-password.email') }}</label>
                    <input type="text" v-validate="'required'" class="control" id="email" name="email" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.sign-in.forget-password.email') }}&quot;" value="{{ old('email') }}"  style="width:100%;"/>

                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                </div>
                
                <div class="button-group">
                    <button class="btn btn-xl btn-primary">{{ __('saas::app.super-user.settings.agents.sign-in.forget-password.submit-btn-title') }}</button>
                </div>

                <div class="control-group" style="margin-bottom: 0">
                    <a href="{{ route('super.session.index') }}">
                        <i class="icon primary-back-icon"></i>
                        {{ __('saas::app.super-user.settings.agents.sign-in.forget-password.back-link-title') }}
                    </a>
                </div>
            </div>
        </form>

    </div>

@stop