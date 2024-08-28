<div class="collapse" id="collapseFilters">
    <div class="card card-body">
        <form type="GET" action="{{route('request-logs.index')}}">
            <div class="form-group row pb-3">
                <label for="url"
                       class="col-sm-2 col-form-label">{{ trans('request-logger::logs.filtering_by_url_label') }}</label>
                <div class="col-sm-10">
                    <input type="text" name="url" class="form-control" id="url"
                           value="{{$request->input('url', '')}}">
                </div>
            </div>
            <div class="form-group row pb-3">
                <label for="exclude_urls" class="col-sm-2 col-form-label">
                    {{ trans('request-logger::logs.filtering_by_exclude_urls_label') }}
                </label>
                <div class="col-sm-10">
                    <div id="exclude-url-container">
                        @forelse($request->input('exclude_urls', []) as  $url)
                            <div class="exclude-url-input-group {{ !$loop->first ? 'pt-1' : '' }}">
                                <div class="d-flex justify-content-start gap-1">
                                    <input type="text" name="exclude_urls[]" class="form-control"
                                           id="exclude_urls"
                                           value="{{$url}}">
                                    @if($loop->first)
                                        <button type="button" class="btn btn-success add-exclude-url-field">
                                            <ion-icon name="add-circle-sharp"></ion-icon>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-danger delete-exclude-url-field">
                                            <ion-icon name="trash-sharp"></ion-icon>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="exclude-url-input-group">
                                <div class="d-flex justify-content-start gap-1">
                                    <input type="text" name="exclude_urls[]" class="form-control" id="exclude_urls">
                                    <button type="button" class="btn btn-success add-exclude-url-field">
                                        <ion-icon name="add-circle-sharp"></ion-icon>
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="form-group row pb-3">
                <label for="url" class="col-sm-2 col-form-label">
                    {{ trans('request-logger::logs.filtering_by_method_label') }}
                </label>
                <div class="col-sm-10">
                    <div class="form-check form-check-inline">
                        <input name="methods[]" class="form-check-input" type="checkbox" id="GET" value="GET"
                               @if(in_array('GET', $request->input('methods', []))) checked @endif>
                        <label class="form-check-label" for="GET">GET</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input name="methods[]" class="form-check-input" type="checkbox" id="POST" value="POST"
                               @if(in_array('POST', $request->input('methods', []))) checked @endif>
                        <label class="form-check-label" for="POST">POST</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input name="methods[]" class="form-check-input" type="checkbox" id="PUT" value="PUT"
                               @if(in_array('PUT', $request->input('methods', []))) checked @endif>
                        <label class="form-check-label" for="PUT">PUT</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input name="methods[]" class="form-check-input" type="checkbox" id="DELETE"
                               value="DELETE"
                               @if(in_array('DELETE', $request->input('methods', []))) checked @endif>
                        <label class="form-check-label" for="DELETE">DELETE</label>
                    </div>
                </div>
            </div>
            <div class="form-group row pb-3">
                <label for="response_status_code"
                       class="col-sm-2 col-form-label">{{ trans('request-logger::logs.filtering_by_status_label') }}</label>
                <div class="col-sm-10">
                    <input type="number" name="response_status_code" class="form-control"
                           id="response_status_code"
                           value="{{$request->input('response_status_code', '')}}">
                </div>
            </div>

            <div class="form-group row pb-3">
                <label for="user_id"
                       class="col-sm-2 col-form-label">{{ trans('request-logger::logs.filtering_by_user_id_label') }}</label>
                <div class="col-sm-10">
                    <input type="number" name="user_id" class="form-control"
                           id="user_id"
                           value="{{$request->input('user_id', '')}}">
                </div>
            </div>

            <div class="form-group row pb-3">
                <label for="fingerprint"
                       class="col-sm-2 col-form-label">{{ trans('request-logger::logs.filtering_by_fingerprint_label') }}</label>
                <div class="col-sm-10">
                    <input type="text" name="fingerprint" class="form-control"
                           id="fingerprint"
                           value="{{$request->input('fingerprint', '')}}">
                </div>
            </div>
            <div class="form-group row pb-3">
                <label for="exclude_fingerprints"
                       class="col-sm-2 col-form-label">{{ trans('request-logger::logs.filtering_by_exclude_fingerprints_label') }}</label>
                <div class="col-sm-10">
                    <div id="exclude-fingerprint-container">
                        @forelse($request->input('exclude_fingerprints', []) as  $fingerprint)
                            @if(!empty(trim($fingerprint)))
                                <div class="exclude-fingerprint-input-group {{ !$loop->first ? 'pt-1' : '' }}">
                                    <div class="d-flex justify-content-start gap-1">
                                        <input type="text" name="exclude_fingerprints[]" class="form-control"
                                               id="exclude_fingerprints"
                                               value="{{$fingerprint}}">
                                        @if($loop->first)
                                            <button type="button"
                                                    class="btn btn-success add-exclude-fingerprint-field">
                                                <ion-icon name="add-circle-sharp"></ion-icon>
                                            </button>
                                        @else
                                            <button type="button"
                                                    class="btn btn-danger delete-exclude-fingerprint-field">
                                                <ion-icon name="trash-sharp"></ion-icon>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="exclude-fingerprint-input-group">
                                <div class="d-flex justify-content-start gap-1">
                                    <input type="text" name="exclude_fingerprints[]" class="form-control"
                                           id="exclude_fingerprints">
                                    <button type="button" class="btn btn-success add-exclude-fingerprint-field">
                                        <ion-icon name="add-circle-sharp"></ion-icon>
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="form-group row pb-3">
                <label for="date_from"
                       class="col-sm-2 col-form-label">{{ trans('request-logger::logs.filtering_by_date_from_label') }}</label>
                <div class="col-sm-10">
                    <input type="datetime-local" name="date_from" class="form-control" id="date_from"
                           value="{{$request->input('date_from', '')}}">
                </div>
            </div>
            <div class="form-group row pb-3">
                <label for="date_to"
                       class="col-sm-2 col-form-label">{{ trans('request-logger::logs.filtering_by_date_to_label') }}</label>
                <div class="col-sm-10">
                    <input type="datetime-local" name="date_to" class="form-control" id="date_to"
                           value="{{$request->input('date_to', '')}}">
                </div>
            </div>
            <div class="form-group row pb-3">
                <label for="order"
                       class="col-sm-2 col-form-label">{{ trans('request-logger::logs.ordering') }}</label>
                <div class="col-sm-10">
                    <select class="form-control" name="order" id="">
                        <option value="date|desc"
                                @if($request->get('order', 'date|desc') === 'date|desc') selected @endif>
                            {{ trans('request-logger::logs.ordering_by_date_desc_label') }}
                        </option>
                        <option value="date|asc" @if($request->get('order', '') === 'date|asc') selected @endif>
                            {{ trans('request-logger::logs.ordering_by_date_asc_label') }}
                        </option>
                        <option value="response_status_code|desc"
                                @if($request->get('order', '') === 'response_status_code|desc') selected @endif>
                            {{ trans('request-logger::logs.ordering_by_status_desc_label') }}
                        </option>
                        <option value="response_status_code|asc"
                                @if($request->get('order', '') === 'response_status_code|asc') selected @endif>
                            {{ trans('request-logger::logs.ordering_by_status_asc_label') }}
                        </option>
                        <option value="duration_ms|desc"
                                @if($request->get('order', '') === 'duration_ms|desc') selected @endif>
                            {{ trans('request-logger::logs.ordering_by_duration_desc_label') }}
                        </option>
                        <option value="duration_ms|asc"
                                @if($request->get('order', '') === 'duration_ms|asc') selected @endif>
                            {{ trans('request-logger::logs.ordering_by_duration_asc_label') }}
                        </option>
                        <option value="memory|desc"
                                @if($request->get('order', '') === 'memory|desc') selected @endif>
                            {{ trans('request-logger::logs.ordering_by_memory_desc_label') }}
                        </option>
                        <option value="memory|asc"
                                @if($request->get('order', '') === 'memory|asc') selected @endif>
                            {{ trans('request-logger::logs.ordering_by_memory_asc_label') }}
                        </option>
                        <option value="repeating|desc"
                                @if($request->get('order', '') === 'repeating|desc') selected @endif>
                            {{ trans('request-logger::logs.ordering_by_repeating_desc_label') }}
                        </option>
                        <option value="repeating|asc"
                                @if($request->get('order', '') === 'repeating|asc') selected @endif>
                            {{ trans('request-logger::logs.ordering_by_repeating_asc_label') }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row pb-3">
                <label for="date_to" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <button type="submit"
                            class="btn btn-success">{{ trans('request-logger::logs.filtering_submit') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
