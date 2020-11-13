@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.settings.agents.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/super/agents') }}';"></i>
                    {{ __('saas::app.super-user.settings.agents.confirm-delete-title') }}
                </h1>
            </div>
        </div>

        <div class="page-content">
            <form action="{{ route('super.agents.destroy', $agent->id) }}" method="POST" @submit.prevent="onSubmit">
                @csrf
                <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <label for="password" class="required">
                        {{ __('saas::app.super-user.settings.agents.current-password') }}
                    </label>

                    <input type="password" v-validate="'required'" class="control" id="password" name="password" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.password') }}&quot;"/>

                    <span class="control-error" v-if="errors.has('password')">
                        @{{ errors.first('password') }}
                    </span>
                </div>

                <input type="submit" class="btn btn-md btn-primary" value="{{ __('saas::app.super-user.settings.agents.confirm-delete') }}">
            </form>
        </div>
    </div>
@endsection
