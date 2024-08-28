@extends('request-logs::layouts.default')
@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ trans('request-logger::logs.title') }}</h1>
    </div>
    <div>
        <div class="d-flex">
            <div class="me-auto">
                <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseFilters" role="button"
                   aria-expanded="false" aria-controls="collapseFilters">
                    {{ trans('request-logger::logs.filtering') }}
                </a>
                <a class="btn btn-secondary"
                   href="{{route('request-logs.index')}}">{{ trans('request-logger::logs.filtering_reset') }}</a>
            </div>
            <div>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#clearAllLogsModal">
                    {{ trans('request-logger::logs.clear_all_logs') }}
                </button>
            </div>
        </div>
        <div class="mt-3">
            @include('request-logs::partials.filtering')
        </div>
    </div>

    @include('request-logs::partials.logs')
    @include('request-logs::modals.log-view-modal')
    @include('request-logs::modals.clear-all-logs-modal')
@endsection
