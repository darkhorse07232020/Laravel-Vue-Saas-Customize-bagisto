@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.settings.locales.edit-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('super.locales.update', $locale->id) }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/super/companies') }}';"></i>

                        {{ __('saas::app.super-user.settings.locales.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('saas::app.super-user.settings.locales.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="container">
                    @csrf()

                    {!! view_render_event('bagisto.super.settings.locale.edit.before') !!}

                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('saas::app.super-user.settings.locales.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('saas::app.super-user.settings.locales.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" data-vv-as="&quot;{{ __('saas::app.super-user.settings.locales.code') }}&quot;" value="{{ $locale->code }}" disabled="disabled"/>
                                <input type="hidden" name="code" value="{{ $locale->code }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('saas::app.super-user.settings.locales.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name" data-vv-as="&quot;{{ __('saas::app.super-user.settings.locales.name') }}&quot;" value="{{ old('name') ?: $locale->name }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('direction') ? 'has-error' : '']">
                                <label for="direction" class="required">{{ __('saas::app.super-user.settings.locales.direction') }}</label>
                                <select v-validate="'required'" class="control" id="direction" name="direction" data-vv-as="&quot;{{ __('saas::app.super-user.settings.locales.direction') }}&quot;">
                                    <option value="ltr" {{ old('direction') == 'ltr' ? 'selected' : '' }} title="Text direction left to right">ltr</option>
                                    <option value="rtl" {{ old('direction') == 'rtl' ? 'selected' : '' }} title="Text direction right to left">rtl</option>
                                </select>
                                <span class="control-error" v-if="errors.has('direction')">@{{ errors.first('direction') }}</span>
                            </div>

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.super.settings.locale.edit.after') !!}
                </div>
            </div>
        </form>
    </div>
@stop