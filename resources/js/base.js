let logTree = null;
document.addEventListener('DOMContentLoaded', function () {
    let modalViewData = document.getElementById('modalViewData')
    modalViewData.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-id');
        let treeWrapper = document.getElementById("tree-wrapper");
        treeWrapper.innerHTML = '';
        logTree = jsonTree.create({}, treeWrapper);
        let url = `/request-logs/${id}/show`;
        $.ajax({
            method: 'GET',
            url,
            dataType: 'json'
        }).done(function (jsonData) {
            logTree.loadData(jsonData)
        });
    });
});

$('#expandLogTree').click(function () {
    logTree.expand();
});

$('#collapseLogTree').click(function () {
    logTree.collapse();
});

$('.add-exclude-fingerprint-field').click(function () {
    let inputGroup = `<div class="exclude-fingerprint-input-group pt-1">
        <div class="d-flex justify-content-start gap-1">
            <input type="text" name="exclude_fingerprints[]" class="form-control">
            <button type="button" class="btn btn-danger delete-exclude-fingerprint-field">
                <ion-icon name="trash-sharp"></ion-icon>
            </button>
        </div>
    </div>`;

    $('#exclude-fingerprint-container').append(inputGroup);
});

$('#collapseFilters').on('click', '.delete-exclude-fingerprint-field', function () {
    $(this).closest('.exclude-fingerprint-input-group').remove();
});

$('.add-exclude-url-field').click(function () {
    let inputGroup = `<div class="exclude-url-input-group pt-1">
        <div class="d-flex justify-content-start gap-1">
            <input type="text" name="exclude_urls[]" class="form-control">
            <button type="button" class="btn btn-danger delete-exclude-url-field">
                <ion-icon name="trash-sharp"></ion-icon>
            </button>
        </div>
    </div>`;

    $('#exclude-url-container').append(inputGroup);
});

$('#collapseFilters').on('click', '.delete-exclude-url-field', function () {
    $(this).closest('.exclude-url-input-group').remove();
});
