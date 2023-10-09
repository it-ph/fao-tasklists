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
                    url: `${APP_URL}/permission/store`,
                    data: formdata
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_permission > tbody").empty();
                        $("#tbl_permission_info").hide();
                        $("#tbl_permission_paginate").hide();
                        $('#storePermissionForm')[0].reset();
                        $("#user_id").val(null).trigger("change");
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
        axios(`${APP_URL}/permission/all`).then(function(response) {
            $('#tbl_permission').DataTable().destroy();
            var table;
            console.log(response.data.data)
            response.data.data.forEach(val => {
                table +=
                    `<tr>
                        <td>${val.employee_name}</td>
                        <td>${val.email_address}</td>
                        <td>${val.cluster}</td>
                        <td>${val.client}</td>
                        <td>${val.team_leader}</td>
                        <td>${val.operations_manager}</td>
                        <td>${val.permission}</td>
                        <td class="text-center">${val.action}</td>
                    </tr>`;
            });
            $('#tbl_permission tbody').html(table)
            $('#tbl_permission').DataTable({
                language: {
                    oPaginate: {
                        sNext: '<i class="fa fa-forward"></i>',
                        sPrevious: '<i class="fa fa-backward"></i>',
                        sFirst: '<i class="fa fa-step-backward"></i>',
                        sLast: '<i class="fa fa-step-forward"></i>'
                    },
                },
                "pageLength": 10,
                "pagingType": "full_numbers",
                "scrollX": true,
                fixedColumns: {
                    left: 2
                },
            });
            $('#loader').hide();
            if (response.data.data.length > 0)
                toastr.success(response.data.message);
            else
                toastr.info(response.data.message);
        }).catch(error => {
            toastr.error(null);
        });
    }

    // show data
    this_permission.show = (id) => {
        $('#editPermissionModal').modal('show');
        $('.error').hide();
        $('.error').text('');
        $("#user_id_edit").val(null).trigger("change");
        $("#cluster_id_edit").val(null).trigger("change");
        $("#client_id_edit").val(null).trigger("change");
        $("#tl_id_edit").val(null).trigger("change");
        $("#om_id_edit").val(null).trigger("change");
        $("#permission_edit").val(null).trigger("change");
        $('#btn_update').empty();
        $('#btn_update').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#btn_update').prop("disabled", true);
        axios(`${APP_URL}/permission/show/${id}`).then(function(response) {
            _permission_id = id;
            $("#user_id_edit").val(response.data.data.user_id).trigger("change");
            $("#cluster_id_edit").val(response.data.data.cluster_id).trigger("change");
            $("#client_id_edit").val(response.data.data.client_id).trigger("change");
            $("#tl_id_edit").val(response.data.data.tl_id).trigger("change");
            $("#om_id_edit").val(response.data.data.om_id).trigger("change");
            $("#permission_edit").val(response.data.data.permission).trigger("change");
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
                    url: `${APP_URL}/permission/update/${id}`,
                    data: formdata
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_permission > tbody").empty();
                        $("#tbl_permission_info").hide();
                        $("#tbl_permission_paginate").hide();
                        $('#editPermissionForm')[0].reset();
                        $("#user_id_edit").val(null).trigger("change");
                        $("#cluster_id_edit").val(null).trigger("change");
                        $("#client_id_edit").val(null).trigger("change");
                        $("#tl_id_edit").val(null).trigger("change");
                        $("#om_id_edit").val(null).trigger("change");
                        $("#permission_edit").val(null).trigger("change");
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
                        url: `${APP_URL}/permission/delete/${id}`,
                    })
                    .then(function(response) {
                        console.log(response.data.status)
                        if (response.data.status === 'success') {
                            $('#tbl_permission').DataTable().destroy();
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