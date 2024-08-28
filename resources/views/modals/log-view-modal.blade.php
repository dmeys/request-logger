<div class="modal fade" id="modalViewData" tabindex="-1" aria-labelledby="modalViewDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="modalViewDataLabel">{{ trans('request-logger::modals.log_view.title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <button class="btn btn-outline-secondary"
                            id="expandLogTree">{{ trans('request-logger::modals.log_view.expand_all') }}</button>
                    <button class="btn btn-outline-secondary"
                            id="collapseLogTree">{{ trans('request-logger::modals.log_view.collapse_all') }}</button>
                </div>
                <div id="tree-wrapper"></div>
            </div>
        </div>
    </div>
</div>
