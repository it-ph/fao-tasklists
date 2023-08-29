$(document).ready(function() {
    TASK.load();
});

const TASK = (() => {
    let this_task = {}
    let _task_id;

    // store data
    $('#storeTaskForm').on('submit', function(e) {
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
                    url: `${APP_URL}/my-task/store`,
                    data: formdata
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_task > tbody").empty();
                        $("#tbl_task_info").hide();
                        $("#tbl_task_paginate").hide();
                        $('#storeTaskForm')[0].reset();
                        $("#client_activity_id").val(null).trigger("change");
                        $('#create_button').load(' #create_button');
                        $('.error').hide();
                        $('.error').text('');
                        TASK.load();
                        $('#addTaskModal').modal('hide');
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
    this_task.load = () => {
        var filter_status = $('#status').html();
        axios(`${APP_URL}/my-task/` + filter_status).then(function(response) {
            $('#tbl_task').DataTable().destroy();
            var table;
            console.log(response.data.data)
            response.data.data.forEach(val => {
                table +=
                    `<tr>
                        <td>${val.status}</td>
                        <td class="text-center">${val.action}</td>
                        <td>${val.employee_name}</td>
                        <td>${val.shift_date}</td>
                        <td>${val.date_received}</td>
                        <td>${val.cluster}</td>
                        <td>${val.client}</td>
                        <td>${val.client_activity}</td>
                        <td>${val.description}</td>
                        <td>${val.start_date}</td>
                        <td>${val.end_date}</td>
                        <td>${val.date_completed}</td>
                        <td>${val.actual_handling_time}</td>
                        <td>${val.volume}</td>
                        <td>${val.remarks}</td>
                    </tr>`;
            });
            $('#tbl_task tbody').html(table)
            $('#tbl_task').DataTable({
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
                "order": [3, "desc"],
                "columnDefs": [{ type: 'date', 'targets': [3] }],
                "scrollX": true,
                fixedColumns: {
                    left: 4
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
    this_task.show = (id) => {
        $('#editTaskModal').modal('show');
        $('.error').hide();
        $('.error').text('');
        $('#btn_update').empty();
        $('#btn_update').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#btn_update').prop("disabled", true);
        axios(`${APP_URL}/my-task/show/${id}`).then(function(response) {
            _task_id = id;
            var shift_date = moment(response.data.data.shift_date).format('YYYY-MM-DD');
            var date_received = moment(response.data.data.date_received).format('YYYY-MM-DD');
            var start_date = moment(response.data.data.start_date).format('MM/DD/YYYY hh:mm:ss a');
            var end_date = response.data.data.end_date ? moment(response.data.data.end_date).format('MM/DD/YYYY hh:mm:ss a') : '';
            $('#shift_date_edit').val(shift_date);
            $('#date_received_edit').val(date_received);
            $("#client_id_edit").val(response.data.data.client_id).trigger("change");
            $("#client_activity_id_edit").val(response.data.data.client_activity_id).trigger("change");
            $('#description_edit').text(response.data.data.description);
            $('#start_date_edit').val(start_date);
            $('#end_date_edit').val(end_date);
            $('#actual_handling_time_edit').val(response.data.data.actual_handling_time);
            $('#volume_edit').val(response.data.data.volume);
            $('#remarks_edit').text(response.data.data.remarks);
            $('#btn_update').empty();
            $('#btn_update').append('<i class="fa fa-save"></i> update');
            $('#btn_update').prop("disabled", false);
            $('.error').hide();
            $('.error').text('');
            toastr.success(response.data.message);
        }).catch(error => {
            toastr.error(error);
        });
    }

    // update data
    $('#editTaskForm').on('submit', function(e) {
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
                id = _task_id;
                var formdata = new FormData(this);
                $('.error').hide();
                $('.error').text('');
                $('#btn_update').empty();
                $('#btn_update').append('<i class="fa fa-spinner fa-spin"></i> Updating...');
                $('#btn_update').prop("disabled", true);
                // Send a POST request
                axios({
                    method: 'post',
                    url: `${APP_URL}/my-task/update/${id}`,
                    data: formdata
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_task > tbody").empty();
                        $("#tbl_task_info").hide();
                        $("#tbl_task_paginate").hide();
                        $('#editTaskForm')[0].reset();
                        $("#client_activity_id_edit").val(null).trigger("change");
                        $('#description_edit').text('');
                        TASK.load();
                        $('.error').hide();
                        $('.error').text('');
                        $('#editTaskModal').modal('hide');
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

    // show data to on hold / complete
    this_task.show_stop = (id) => {
        $('.error').hide();
        $('.error').text('');
        $('#stopTaskModal').modal('show');
        _task_id = id
    }

    // stop: on hold / complete
    $('#stopTaskForm').on('submit', function(e) {
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
                id = _task_id;
                var formdata = new FormData(this);
                $('.error').hide();
                $('.error').text('');
                $('#btn_stop').empty();
                $('#btn_stop').append('<i class="fa fa-spinner fa-spin"></i> Stopping...');
                $('#btn_stop').prop("disabled", true);
                // Send a POST request
                axios({
                    method: 'post',
                    url: `${APP_URL}/my-task/stop/${id}`,
                    data: formdata
                }).then(function(response) {
                    console.log(response.data.status)
                    if (response.data.status === 'success') {
                        $('#loader').show();
                        $("#tbl_task > tbody").empty();
                        $("#tbl_task_info").hide();
                        $("#tbl_task_paginate").hide();
                        $('#stopTaskForm')[0].reset();
                        $("#status_stop").val(null).trigger("change");
                        $('#remarks_stop').text('');
                        $('#create_button').load(' #create_button');
                        $('.error').hide();
                        $('.error').text('');
                        TASK.load();
                        $('#stopTaskModal').modal('hide');
                        toastr.success(response.data.message);
                    } else if (response.data.status === 'warning') {
                        Object.keys(response.data.error).forEach((key) => {
                            $(`#${[key]}_stopError`).show();
                            $(`#${[key]}_stopError`).text(response.data.error[key][0]);
                        });
                    } else {
                        toastr.error(response.data.message);
                    }
                    $('#btn_stop').empty();
                    $('#btn_stop').append('<i class="fa fa-save"></i> Stop');
                    $('#btn_stop').prop("disabled", false);
                }).catch(error => {
                    toastr.error(error);
                });
            }
        });
    });

    return this_task;
})()