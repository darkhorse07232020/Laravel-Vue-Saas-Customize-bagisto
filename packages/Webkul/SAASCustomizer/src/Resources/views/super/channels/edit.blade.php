@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.settings.channels.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('super.channels.update', $superChannel->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/super/tenants') }}';"></i>

                        {{ __('saas::app.super-user.settings.channels.edit-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('saas::app.super-user.settings.channels.btn-save') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('saas::app.super-user.settings.channels.general') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('saas::app.super-user.settings.channels.code') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="code" name="code" data-vv-as="&quot;{{ __('saas::app.super-user.settings.channels.code') }}&quot;" value="{{ $superChannel->code }}" disabled="disabled"/>
                                <input type="hidden" name="code" value="{{ $superChannel->code }}"/>
                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('saas::app.super-user.settings.channels.name') }}</label>
                                <input v-validate="'required'" class="control" id="name" name="name" data-vv-as="&quot;{{ __('saas::app.super-user.settings.channels.name') }}&quot;" value="{{ old('name') ?: $superChannel->name }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('hostname') ? 'has-error' : '']">
                                <label for="hostname">{{ __('saas::app.super-user.settings.channels.hostname') }}</label>
                                <input type="text" v-validate="''" class="control" id="hostname" name="hostname" value="{{ $superChannel->hostname }}" placeholder="https://www.example.com"/>

                                <span class="control-error" v-if="errors.has('hostname')">@{{ errors.first('hostname') }}</span>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('saas::app.super-user.settings.channels.currencies-and-locales') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('locales[]') ? 'has-error' : '']">
                                <label for="locales" class="required">{{ __('saas::app.super-user.settings.channels.locales') }}</label>
                                <?php $selectedOptionIds = old('locales') ?: $superChannel->locales->pluck('id')->toArray() ?>
                                <select v-validate="'required'" class="control" id="locales" name="locales[]" data-vv-as="&quot;{{ __('saas::app.super-user.settings.channels.locales') }}&quot;" multiple>
                                    @foreach (company()->getAllLocales() as $locale)
                                        <option value="{{ $locale->id }}" {{ in_array($locale->id, $selectedOptionIds) ? 'selected' : '' }}>
                                            {{ $locale->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('locales[]')">@{{ errors.first('locales[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('default_locale_id') ? 'has-error' : '']">
                                <label for="default_locale_id" class="required">{{ __('saas::app.super-user.settings.channels.default-locale') }}</label>
                                <?php $selectedOption = old('default_locale_id') ?: $superChannel->default_locale_id ?>
                                <select v-validate="'required'" class="control" id="default_locale_id" name="default_locale_id" data-vv-as="&quot;{{ __('saas::app.super-user.settings.channels.default-locale') }}&quot;">
                                    @foreach (company()->getAllLocales() as $locale)
                                        <option value="{{ $locale->id }}" {{ $selectedOption == $locale->id ? 'selected' : '' }}>
                                            {{ $locale->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('default_locale_id')">@{{ errors.first('default_locale_id') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('currencies[]') ? 'has-error' : '']">
                                <label for="currencies" class="required">{{ __('saas::app.super-user.settings.channels.currencies') }}</label>
                                <?php $selectedOptionIds = old('currencies') ?: $superChannel->currencies->pluck('id')->toArray() ?>

                                <select v-validate="'required'" class="control" id="currencies" name="currencies[]" data-vv-as="&quot;{{ __('saas::app.super-user.settings.channels.currencies') }}&quot;" multiple>
                                    @foreach (company()->getAllCurrencies() as $currency)
                                        <option value="{{ $currency->id }}" {{ in_array($currency->id, $selectedOptionIds) ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('currencies[]')">@{{ errors.first('currencies[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('base_currency_id') ? 'has-error' : '']">
                                <label for="base_currency_id" class="required">{{ __('saas::app.super-user.settings.channels.default-currency') }}</label>
                                <?php $selectedOption = old('base_currency_id') ?: $superChannel->base_currency_id ?>
                                <select v-validate="'required'" class="control" id="base_currency_id" name="base_currency_id" data-vv-as="&quot;{{ __('saas::app.super-user.settings.channels.default-currency') }}&quot;">
                                    @foreach (company()->getAllCurrencies() as $currency)
                                        <option value="{{ $currency->id }}" {{ $selectedOption == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('base_currency_id')">@{{ errors.first('base_currency_id') }}</span>
                            </div>

                        </div>
                    </accordian>

                    <accordian :title="'{{ __('saas::app.super-user.settings.channels.design') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group">
                                <label for="home_page_content">{{ __('saas::app.super-user.settings.channels.home-page-content') }}</label>
                                <textarea class="control" id="home_page_content" name="home_page_content">{{ old('home_page_content') ?: $superChannel->home_page_content }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="footer_page_content">{{ __('saas::app.super-user.settings.channels.footer-page-content') }}</label>
                                <textarea class="control" id="footer_page_content" name="footer_page_content">{{ old('footer_page_content') ?: $superChannel->footer_page_content }}</textarea>
                            </div>

                            <div class="control-group">
                                <label>{{ __('saas::app.super-user.settings.channels.logo') }}</label>

                                <image-wrapper button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="logo" :multiple="false" @if(isset($superChannel->logo_url)) :images='"{{ $superChannel->logo_url }}"' @endif></image-wrapper>
                            </div>

                            <div class="control-group">
                                <label>{{ __('saas::app.super-user.settings.channels.favicon') }}</label>

                                <image-wrapper button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="favicon" :multiple="false" @if(isset($superChannel->favicon_url)) :images='"{{ $superChannel->favicon_url }}"' @endif></image-wrapper>
                            </div>

                        </div>
                    </accordian>

                    @php
                        $seo = json_decode($superChannel->home_seo);
                    @endphp

                    <accordian :title="'{{ __('saas::app.super-user.settings.channels.home-page-seo') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('meta_title') ? 'has-error' : '']">
                                <label for="meta_title" class="required">
                                    {{ __('saas::app.super-user.settings.channels.meta-title') }}
                                </label>

                                <input v-validate="'required|max:60'" class="control" id="meta_title" name="meta_title" data-vv-as="&quot;{{ __('saas::app.super-user.settings.channels.meta-title') }}&quot;" value="{{ isset($seo->meta_title) ? $seo->meta_title : '' }}"/>

                                <span class="control-error" v-if="errors.has('meta_title')">@{{ errors.first('meta_title') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('meta_keywords') ? 'has-error' : '']">
                                <label for="meta_keywords" class="required">{{ __('saas::app.super-user.settings.channels.meta-keywords') }}</label>

                                <textarea v-validate="'required|max:160'" class="control" id="meta_keywords" name="meta_keywords" data-vv-as="&quot;{{ __('saas::app.super-user.settings.channels.meta-keywords') }}&quot;">{{ isset($seo->meta_keywords) ? $seo->meta_keywords : '' }}</textarea>

                                <span class="control-error" v-if="errors.has('meta_keywords')">@{{ errors.first('meta_keywords') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('meta_description') ? 'has-error' : '']">
                                <label for="meta_description">{{ __('saas::app.super-user.settings.channels.meta-description') }}</label>

                                <textarea v-validate="'max:160'" class="control" id="meta_description" name="meta_description" data-vv-as="&quot;{{ __('saas::app.super-user.settings.channels.meta-description') }}&quot;">{{ isset($seo->meta_description) ? $seo->meta_description : '' }}</textarea>

                                <span class="control-error" v-if="errors.has('meta_description')">@{{ errors.first('meta_description') }}</span>
                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: 'textarea#home_page_content,textarea#footer_page_content',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true,
                valid_elements : '*[*]'
            });
        });
    </script>
@endpush