<div class="card card-body mt-3">
    <h5 class="card-title">{{ trans('request-logger::logs.total_title', ['total' => $total]) }}</h5>
    <div class="table-responsive">
        <table id="request-logs" class="table table-sm">
            <thead>
            <tr>
                <th>{{ trans('request-logger::logs.url_column_title') }}</th>
                <th>{{ trans('request-logger::logs.status_column_title') }}</th>
                <th>{{ trans('request-logger::logs.duration_column_title') }}</th>
                <th>{{ trans('request-logger::logs.memory_column_title') }}</th>
                <th>{{ trans('request-logger::logs.ip_column_title') }}</th>
                <th>{{ trans('request-logger::logs.date_column_title') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($logs as $log)
                <tr>
                    <td class="column-url">
                        <div>
                            <b class="{{ $log->methodClass }}">{{ $log->method }}</b> {{ $log->url_decoded }}
                        </div>
                        <div>
                            <span class="badge bg-secondary rounded-circle">{{ $log->repeating }}</span>
                            {{ $log->fingerprint }}
                        </div>
                    </td>
                    <td class="column-status-code">
                        <span class="badge {{ $log->status_class }}">{{ $log->response_status_code }}</span>
                    </td>
                    <td class="column-duration">{{ $log->duration_ms }} ms</td>
                    <td class="column-memory">{{ $log->memory }} MB</td>
                    <td>{{ $log->ip }}</td>
                    <td>
                        <div>{{ $log->timeString }}</div>
                        <div>{{ $log->dateString }}</div>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#modalViewData"
                               data-id="{{ $log->id }}">
                                <ion-icon name="eye-sharp"></ion-icon>
                            </a>
                            <a class="btn btn-success m-1"
                               href="{{ route('request-logs.download', ['id' => $log->id]) }}">
                                <ion-icon name="download-sharp"></ion-icon>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
            @if ($logs->hasPages())
                <tfoot>
                <tr>
                    <td colspan="7">
                        {{ $logs->appends($request->except('page'))->links() }}
                    </td>
                </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
