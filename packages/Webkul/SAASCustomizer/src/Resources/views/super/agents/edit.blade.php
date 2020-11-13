@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.settings.agents.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('super.agents.update', $agent->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/super/companies') }}';"></i>

                        {{ __('saas::app.super-user.settings.agents.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('saas::app.super-user.settings.agents.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="container">
                    @csrf()

                    {!! view_render_event('bagisto.super.settings.agent.edit.before') !!}

                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('saas::app.super-user.settings.locales.general') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                                <label for="first_name" class="required">{{ __('saas::app.super-user.settings.agents.first-name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="first_name" name="first_name" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.first-name') }}&quot;"  value="{{ $agent->first_name }}"/>
                                <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                                <label for="last_name" class="required">{{ __('saas::app.super-user.settings.agents.last-name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="last_name" name="last_name" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.last-name') }}&quot;"  value="{{ $agent->last_name }}" />
                                <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email" class="required">{{ __('saas::app.super-user.settings.agents.email') }}</label>
                                <input type="text" v-validate="'required|email'" class="control" id="email" name="email" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.email') }}&quot;" value="{{ $agent->email }}" />
                                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('saas::app.super-user.settings.agents.password') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('old_password') ? 'has-error' : '']">
                                <label for="old_password" class="required">{{ __('saas::app.super-user.settings.agents.old-password') }}</label>
                                <input type="password" v-validate="'required|min:6|max:18'" class="control" id="old_password" name="old_password" ref="password" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.old-password') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('old_password')">@{{ errors.first('old_password') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                                <label for="password">{{ __('saas::app.super-user.settings.agents.new-password') }}</label>
                                <input type="password" v-validate="'min:6|max:18'" class="control" id="password" name="password" ref="password" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.new-password') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                                <label for="password_confirmation">{{ __('saas::app.super-user.settings.agents.confirm-password') }}</label>
                                <input type="password" v-validate="'min:6|max:18|confirmed:password'" class="control" id="password_confirmation" name="password_confirmation" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.confirm-password') }}&quot;"/>
                                <span class="control-error" v-if="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('saas::app.super-user.settings.agents.status-and-role') }}'" :active="true">
                        <div slot="body">
                            {{--  <div class="control-group" :class="[errors.has('role_id') ? 'has-error' : '']">
                                <label for="role">{{ __('saas::app.super-user.settings.agents.role') }}</label>
                                <select class="control" name="role_id" data-vv-as="&quot;{{ __('saas::app.super-user.settings.agents.role') }}&quot;">
                                    
                                </select>
                                <span class="control-error" v-if="errors.has('role_id')">@{{ errors.first('role_id') }}</span>
                            </div>  --}}

                            <div class="control-group">
                                <label for="status">{{ __('saas::app.super-user.settings.agents.status') }}</label>
                                <span class="checkbox">
                                    <input type="checkbox" id="status" name="status"

                                    {{ $agent->status ? 'checked' : '' }}>

                                    <label class="checkbox-view" for="status"></label>
                                    {{ __('saas::app.super-user.settings.agents.account-is-active') }}
                                </span>
                            </div>
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.super.settings.agent.edit.after') !!}

                </div>
            </div>
        </form>
    </div>
@stop