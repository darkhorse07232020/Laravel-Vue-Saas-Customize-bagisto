@extends('saas::companies.layouts.master')

@php
    $channel = company()->getCurrentChannel();
    
    if ( $channel ) {
        $homeSEO = $channel->home_seo;

        if (isset($homeSEO)) {
            $homeSEO = json_decode($channel->home_seo);

            $metaTitle = $homeSEO->meta_title;

            $metaDescription = $homeSEO->meta_description;

            $metaKeywords = $homeSEO->meta_keywords;
        }
    }
@endphp

@section('page_title')
    {{ isset($metaTitle) ? $metaTitle : "" }}
@endsection

@section('head')

    @if (isset($homeSEO))
        @isset($metaTitle)
            <meta name="title" content="{{ $metaTitle }}" />
        @endisset

        @isset($metaDescription)
            <meta name="description" content="{{ $metaDescription }}" />
        @endisset

        @isset($metaKeywords)
            <meta name="keywords" content="{{ $metaKeywords }}" />
        @endisset
    @endif
@endsection

@section('content-wrapper')
    {!! view_render_event('bagisto.saas.companies.home.content.before') !!}

    @if ( $channel ) 
        {{--  {!! DbView::make($channel)->field('home_page_content')->with(['sliderData' => $sliderData])->render() !!}  --}}
        {!! DbView::make($channel)->field('home_page_content')->render() !!}
    @endif

    {{ view_render_event('bagisto.saas.companies.home.content.after') }}

@endsection

