'use strict'

/**
 * Datatable handle delete request
 * @param event
 * @param id
 */
function makeDeleteRequest(event, id) {
    event.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover!",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        icon: 'success',
        width: '24em'
    }).then(function (result) {
        if (result.value) {
            let form_id = $('#delete-form-' + id);
            $(form_id).submit();
        }
    });
}

$(document).ready(function () {
    $("#dt-html-builder").wrap('<div class="table-responsive"></div>');
});