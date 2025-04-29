$(document).ready(function() {
    CLUSTER.load();
});

const CLUSTER = (() => {
    let this_cluster = {}
    let _cluster_id;

    // store data
    $('#storeClusterForm').on('submit', function(e) {
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
                    url: `${APP_URL}/cluster/store`,
                    data: formdata
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_cluster > tbody").empty();
                        $("#tbl_cluster_info").hide();
                        $("#tbl_cluster_paginate").hide();
                        $('#storeClusterForm')[0].reset();
                        $("#name").val('');
                        $('.error').hide();
                        $('.error').text('');
                        CLUSTER.load();
                        $('#addClusterModal').modal('hide');
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
    this_cluster.load = () => {
        $.fn.dataTable.ext.errMode = 'none';

        $('#tbl_cluster').DataTable().clear().draw();
        $('#tbl_cluster').DataTable().destroy();
        $('#tbl_cluster').DataTable({
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
                url: `${APP_URL}/cluster/api/all`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'action', name: 'action', className: 'text-center' },
            ],
            dom: `
                <"d-flex justify-content-between align-items-center mb-2"
                    <"d-flex align-items-center gap-2 left-section"B l>
                    <"right-section"f>
                >
                rt
                <"d-flex justify-content-between align-items-center mt-2"
                    <"info-section"i>
                    <"pagination-section"p>
                >
            `,
            buttons: [
                'excel',
            ]
        });

        $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
            console.log(message);
        };
    }

    // show data
    this_cluster.show = (id) => {
        $('#editClusterModal').modal('show');
        $('.error').hide();
        $('.error').text('');
        $("#name_edit").val('');
        $('#btn_update').empty();
        $('#btn_update').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#btn_update').prop("disabled", true);
        axios(`${APP_URL}/cluster/show/${id}`).then(function(response) {
            _cluster_id = id;
            $("#name_edit").val(response.data.data.name);
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
    $('#editClusterForm').on('submit', function(e) {
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
                id = _cluster_id;
                var formdata = new FormData(this);
                $('.error').hide();
                $('.error').text('');
                $('#btn_update').empty();
                $('#btn_update').append('<i class="fa fa-spinner fa-spin"></i> Updating...');
                $('#btn_update').prop("disabled", true);
                // Send a POST request
                axios({
                    method: 'post',
                    url: `${APP_URL}/cluster/update/${id}`,
                    data: formdata
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_cluster > tbody").empty();
                        $("#tbl_cluster_info").hide();
                        $("#tbl_cluster_paginate").hide();
                        $('#editClusterForm')[0].reset();
                        $("#name_edit").val('');
                        CLUSTER.load();
                        $('.error').hide();
                        $('.error').text('');
                        $('#editClusterModal').modal('hide');
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
    this_cluster.destroy = (id) => {
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
                        url: `${APP_URL}/cluster/delete/${id}`,
                    })
                    .then(function(response) {
                        console.log(response.data.status)
                        if (response.data.status === 'success') {
                            $('#tbl_cluster').DataTable().destroy();
                            toastr.success(response.data.message);
                            CLUSTER.load();
                        } else {
                            toastr.error(response.data.message);
                        }
                    }).catch(error => {
                        toastr.error(null);
                    });
            }
        });
    }

    return this_cluster;
})()