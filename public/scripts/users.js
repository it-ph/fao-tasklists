$(document).ready(function() {
    PERMISSION.load();
});

const PERMISSION = (() => {
    let this_permission = {}
    let _permission_id;

    // store data
    $('#storePermissionForm').on('submit', function(e) {
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
                    url: `${APP_URL}/user/store`,
                    data: formdata
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_user > tbody").empty();
                        $("#tbl_user_info").hide();
                        $("#tbl_user_paginate").hide();
                        $('#storePermissionForm')[0].reset();
                        $("#cluster_id").val(null).trigger("change");
                        $("#client_id").val(null).trigger("change");
                        $("#tl_id").val(null).trigger("change");
                        $("#om_id").val(null).trigger("change");
                        $("#permission").val(null).trigger("change");
                        $('.error').hide();
                        $('.error').text('');
                        PERMISSION.load();
                        $('#addPermissionModal').modal('hide');
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
    this_permission.load = () => {
        $.fn.dataTable.ext.errMode = 'none';

        $('#tbl_user').DataTable().clear().draw();
        $('#tbl_user').DataTable().destroy();
        $('#tbl_user').DataTable({
            // "bStateSave": true,
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw mt-3"></i><span class="sr-only">Loading...</span> ',
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
            order: [0, "asc"],
            processing: true,
            serverSide: true,
            ajax: {
                url: `${APP_URL}/user/api/all`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            },
            columns: [
                { data: 'fullname', name: 'fullname' },
                { data: 'email', name: 'email' },
                { data: 'cluster_id', name: 'thecluster.name' },
                { data: 'client_id', name: 'theclient.name' },
                { data: 'thetl', name: 'thetl' },
                { data: 'theom', name: 'theom' },
                { data: 'permission', name: 'permission' },
                { data: 'status', name: 'status' },
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

    // show data
    this_permission.show = (id) => {
        $('#editPermissionModal').modal('show');
        $('.error').hide();
        $('.error').text('');
        $('#editPermissionForm')[0].reset();
        $("#cluster_id_edit").val(null).trigger("change");
        $("#client_id_edit").val(null).trigger("change");
        $("#tl_id_edit").val(null).trigger("change");
        $("#om_id_edit").val(null).trigger("change");
        $("#permission_edit").val(null).trigger("change");
        $("#sstatus_edit").val(null).trigger("change");
        $('#btn_update').empty();
        $('#btn_update').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#btn_update').prop("disabled", true);
        axios(`${APP_URL}/user/show/${id}`).then(function(response) {
            _permission_id = id;
            $("#fullname_edit").val(response.data.data.fullname);
            $("#email_edit").val(response.data.data.email);
            $("#cluster_id_edit").val(response.data.data.cluster_id).trigger("change");
            $("#client_id_edit").val(response.data.data.client_id).trigger("change");
            $("#tl_id_edit").val(response.data.data.tl_id).trigger("change");
            $("#om_id_edit").val(response.data.data.om_id).trigger("change");
            $("#permission_edit").val(response.data.data.permission).trigger("change");
            $("#sstatus_edit").val(response.data.data.status).trigger("change");
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

    // update data
    $('#editPermissionForm').on('submit', function(e) {
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
                id = _permission_id;
                var formdata = new FormData(this);
                $('.error').hide();
                $('.error').text('');
                $('#btn_update').empty();
                $('#btn_update').append('<i class="fa fa-spinner fa-spin"></i> Updating...');
                $('#btn_update').prop("disabled", true);
                // Send a POST request
                axios({
                    method: 'post',
                    url: `${APP_URL}/user/update/${id}`,
                    data: formdata
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_user > tbody").empty();
                        $("#tbl_user_info").hide();
                        $("#tbl_user_paginate").hide();
                        $('#editPermissionForm')[0].reset();
                        $("#cluster_id_edit").val(null).trigger("change");
                        $("#client_id_edit").val(null).trigger("change");
                        $("#tl_id_edit").val(null).trigger("change");
                        $("#om_id_edit").val(null).trigger("change");
                        $("#permission_edit").val(null).trigger("change");
                        $("#sstatus_edit").val(null).trigger("change");
                        PERMISSION.load();
                        $('.error').hide();
                        $('.error').text('');
                        $('#editPermissionModal').modal('hide');
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

    // destroy data
    this_permission.destroy = (id) => {
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
                        url: `${APP_URL}/user/delete/${id}`,
                    })
                    .then(function(response) {
                        console.log(response.data.status)
                        if (response.data.status === 'success') {
                            $('#tbl_user').DataTable().destroy();
                            toastr.success(response.data.message);
                            PERMISSION.load();
                        } else {
                            toastr.error(response.data.message);
                        }
                    }).catch(error => {
                        toastr.error(null);
                    });
            }
        });
    }

    return this_permission;
})()
