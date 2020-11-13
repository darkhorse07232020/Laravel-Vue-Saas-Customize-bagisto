@extends('saas::super.layouts.content')

@section('page_title')
    {{ __('saas::app.super-user.settings.agents.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('saas::app.super-user.settings.agents.title') }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('super.agents.create') }}" class="btn btn-lg btn-primary">
                    {{ __('saas::app.super-user.settings.agents.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">

            @inject('datagrid','Webkul\SAASCustomizer\DataGrids\AgentDataGrid')
            {!! $datagrid->render() !!}
        </div>
    </div>

@stop
