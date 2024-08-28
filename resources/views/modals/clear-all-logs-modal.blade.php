<div class="modal fade" id="clearAllLogsModal" tabindex="-1" aria-labelledby="clearAllLogsModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('request-logger::modals.clear_all_logs.title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ trans('request-logger::modals.clear_all_logs.message') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ trans('request-logger::modals.clear_all_logs.dismiss') }}
                </button>
                <a href="{{route('request-logs.clearAllLogs')}}" class="btn btn-danger">
                    {{ trans('request-logger::modals.clear_all_logs.confirm') }}
                </a>
            </div>
        </div>
    </div>
</div>
