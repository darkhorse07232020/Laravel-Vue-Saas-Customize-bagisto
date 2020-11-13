@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.settings.currencies.edit-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('super.currencies.update', $currency->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/super/companies') }}';"></i>

                        {{ __('saas::app.super-user.settings.currencies.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('saas::app.super-user.settings.currencies.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    {!! view_render_event('bagisto.admin.settings.currencies.edit.before') !!}

                    <accordian :title="'{{ __('saas::app.super-user.settings.currencies.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('saas::app.super-user.settings.currencies.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" data-vv-as="&quot;{{ __('saas::app.super-user.settings.currencies.code') }}&quot;" value="{{ old('code') ?: $currency->code }}" disabled="disabled"/>
                                <input type="hidden" name="code" value="{{ $currency->code }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('saas::app.super-user.settings.currencies.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name" data-vv-as="&quot;{{ __('saas::app.super-user.settings.currencies.name') }}&quot;" value="{{ old('name') ?: $currency->name }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="symbol">{{ __('saas::app.super-user.settings.currencies.symbol') }}</label>
                                <input class="control" id="symbol" name="symbol" value="{{ old('symbol') ?: $currency->symbol }}"/>
                            </div>
                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.settings.currencies.edit.after') !!}
                </div>
            </div>
        </form>
    </div>
@stop