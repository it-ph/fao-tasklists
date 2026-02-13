$(document).ready(function() {
    CHANGEREQUEST.load();
});

const CHANGEREQUEST = (() => {
    let this_change_request = {}
    let _change_request_id;

    // store data
    $('#storeChangeRequestForm').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#00599D",
            cancelButtonColor: "#F46A6A",
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'No, cancel!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                var formdata = new FormData(this);
                $('.error').hide();
                $('.error').text('');
                $('#btn_save').empty();
                $('#btn_save').append('<i class="fa fa-spinner fa-spin"></i> Saving...');
                $('#btn_save').prop("disabled", true);
                // Send a POST request
                axios({
                    method: 'post',
                    url: `${APP_URL}/change-request/store`,
                    data: formdata
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_change_request > tbody").empty();
                        $("#tbl_change_request_info").hide();
                        $("#tbl_change_request_paginate").hide();
                        $('#storeChangeRequestForm')[0].reset();
                        $("#task_id").val(null).trigger("change");
                        $('.error').hide();
                        $('.error').text('');
                        refreshChangeRequestCount();
                        CHANGEREQUEST.load();
                        $('#addChangeRequestModal').modal('hide');
                        toastr.success(response.data.message);
                    } else if (response.data.status === 'warning') {
                        Object.keys(response.data.error).forEach((key) => {
                            $(`#${[key]}Error`).show();
                            $(`#${[key]}Error`).text(response.data.error[key][0]);
                        });
                    } else {
                        toastr.error(response.data.message);
                    }
                    $('#btn_save').empty();
                    $('#btn_save').append('<i class="fa fa-save"></i> Save');
                    $('#btn_save').prop("disabled", false);
                }).catch(error => {
                    toastr.error(error);
                });
            }
        });
    });

    // load data
    this_change_request.load = () => {
        $.fn.dataTable.ext.errMode = 'none';

        $('#tbl_change_request').DataTable().clear().draw();
        $('#tbl_change_request').DataTable().destroy();
        $('#tbl_change_request').DataTable({
            // "bStateSave": true,
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                oPaginate: {
                    sNext: '<i class="fa fa-forward"></i>',
                    sPrevious: '<i class="fa fa-backward"></i>',
                    sFirst: '<i class="fa fa-step-backward"></i>',
                    sLast: '<i class="fa fa-step-forward"></i>'
                },
            },
            scrollX: true,
            pagingType: "full_numbers",
            pageLength: 20,
            lengthMenu: [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            // order: [4, "desc"],
            processing: true,
            serverSide: true,
            ajax: {
                url: `${APP_URL}/change-request/api/all`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            },
            columns: [
                { data: 'task_id', name: 'task_id', className: 'text-center' },
                { data: 'thecreatedby.fullname', name: 'thecreatedby.fullname' },
                { data: 'thecluster.name', name: 'thecluster.name' },
                { data: 'remarks', name: 'remarks' },
                { data: 'status', name: 'status', className: 'text-center' },
                { data: 'created_at', name: 'created_at', className: 'text-center' },
                { data: 'closed_at', name: 'closed_at', className: 'text-center' },
                { data: 'changed_by', name: 'thechangedby.fullname' },
                { data: 'action', name: 'action', className: 'text-center' },
            ],
            dom: 'Blfrtip',
            buttons: [{
                extend: 'excel',
                text: '<i class="fa fa-download"></i> Export',
                exportOptions: {
                    // This will exclude the last column (Action)
                    columns: ':not(:last-child)' // Excludes the last column (Action)
                }
            }],
        });

        $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
            console.log(message);
        };
    }

    // edit data
    this_change_request.edit = (id) => {
        $('#editChangeRequestModal').modal('show');
        $('.error').hide();
        $('.error').text('');
        $("#cr_update_close").val("update");
        $("#task_id_edit").val(null).trigger("change");
        $("#remarks_edit").text(null);
        $('#task_id_edit').prop("disabled", false);
        $('#remarks_edit').removeAttr('readonly');
        $('#btn_update').empty();
        $('#btn_update').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#btn_update').prop("disabled", true);
        $('#btn_update').prop("disabled", true);
        axios(`${APP_URL}/change-request/show/${id}`).then(function(response) {
            _change_request_id = id;
            $("#task_id_edit").val(response.data.data.task_id).trigger("change");
            $("#remarks_edit").text(response.data.data.remarks);
            $('#btn_update').empty();
            $('#btn_update').append('<i class="fa fa-save"></i> Update');
            $('#btn_update').prop("disabled", false);
            $('.error').hide();
            $('.error').text('');
            toastr.success(response.data.message);
        }).catch(error => {
            toastr.error(error);
        });
    }

    // show data
    this_change_request.show = (id) => {
        $('#editChangeRequestModal').modal('show');
        $('.error').hide();
        $('.error').text('');
        $("#cr_update_close").val("close");
        $("#task_id_edit").val(null).trigger("change");
        $('#task_id_edit').prop("disabled", true);
        // $('#remarks_edit').attr('readonly', 'true');
        $("#remarks_edit").text(null);
        $('#btn_update').empty();
        $('#btn_update').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#btn_update').prop("disabled", true);
        $('#btn_update').prop("disabled", true);
        axios(`${APP_URL}/change-request/show/${id}`).then(function(response) {
            _change_request_id = id;
            $("#task_id_edit").val(response.data.data.task_id).trigger("change");
            $("#remarks_edit").text(response.data.data.remarks + '\n');
            $('#btn_update').empty();
            $('#btn_update').append('<i class="fa fa-check"></i> Mark as Closed');
            $('#btn_update').prop("disabled", false);
            $('.error').hide();
            $('.error').text('');
            toastr.success(response.data.message);
        }).catch(error => {
            toastr.error(error);
        });
    }

    // update data
    $('#editChangeRequestForm').on('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#00599D",
            cancelButtonColor: "#F46A6A",
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'No, cancel!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                id = _change_request_id;
                var formdata = new FormData(this);
                var cr_update_close = $("#cr_update_close").val();
                $('.error').hide();
                $('.error').text('');
                $('#btn_update').empty();
                $('#btn_update').append('<i class="fa fa-spinner fa-spin"></i> Updating...');
                $('#btn_update').prop("disabled", true);
                // Send a POST request
                axios({
                    method: 'post',
                    url: `${APP_URL}/change-request/${cr_update_close}/${id}`,
                    data: formdata
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_change_request > tbody").empty();
                        $("#tbl_change_request_info").hide();
                        $("#tbl_change_request_paginate").hide();
                        $('#editChangeRequestForm')[0].reset();
                        $("#name_edit").val('');
                        refreshChangeRequestCount();
                        CHANGEREQUEST.load();
                        $('.error').hide();
                        $('.error').text('');
                        $('#editChangeRequestModal').modal('hide');
                        toastr.success(response.data.message);
                    } else if (response.data.status === 'warning') {
                        Object.keys(response.data.error).forEach((key) => {
                            $(`#${[key]}_editError`).show();
                            $(`#${[key]}_editError`).text(response.data.error[key][0]);
                        });
                    } else {
                        toastr.error(response.data.message);
                    }
                    $('#btn_update').empty();
                    $('#btn_update').append('<i class="fa fa-save"></i> Update');
                    $('#btn_update').prop("disabled", false);
                }).catch(error => {
                    toastr.error(error);
                });
            }
        });
    });

    // close change request
    this_change_request.close = (id) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#00599D",
            cancelButtonColor: "#F46A6A",
            confirmButtonText: 'Yes, close it!',
            cancelButtonText: 'No, cancel!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btn-close-' + id).empty();
                $('#btn-close-' + id).append('<i class="fa fa-spinner fa-spin"></i>');
                $('#btn-close-' + id).prop("disabled", true);
                // Send a POST request
                axios({
                    method: 'post',
                    url: `${APP_URL}/change-request/close/${id}`,
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_change_request > tbody").empty();
                        $("#tbl_change_request_info").hide();
                        $("#tbl_change_request_paginate").hide();
                        refreshChangeRequestCount();
                        CHANGEREQUEST.load();
                        $('.error').hide();
                        $('.error').text('');
                        toastr.success(response.data.message);
                    } else if (response.data.status === 'warning') {
                        Object.keys(response.data.error).forEach((key) => {
                            $(`#${[key]}_editError`).show();
                            $(`#${[key]}_editError`).text(response.data.error[key][0]);
                        });
                    } else {
                        toastr.error(response.data.message);
                    }
                    $('#btn-close-' + id).empty();
                    $('#btn-close-' + id).append('<i class="fa fa-check"></i>');
                    $('#btn-close-' + id).prop("disabled", false);
                }).catch(error => {
                    toastr.error(error);
                });
            }
        });
    }

    // destroy data
    this_change_request.destroy = (id) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#00599D",
            cancelButtonColor: "#F46A6A",
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                axios({
                        method: 'post',
                        url: `${APP_URL}/change-request/delete/${id}`,
                    })
                    .then(function(response) {
                        console.log(response.data.status)
                        if (response.data.status === 'success') {
                            $('#tbl_change_request').DataTable().destroy();
                            toastr.success(response.data.message);
                            CHANGEREQUEST.load();
                        } else {
                            toastr.error(response.data.message);
                        }
                    }).catch(error => {
                        toastr.error(null);
                    });
            }
        });
    }

    return this_change_request;
})()
