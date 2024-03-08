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
                        $("#client_id").val(null).trigger("change");
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
                    $('#btn_save').append('<i class="fa fa-save"></i> Save and Start');
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

            $('#tbl_task thead tr:eq(1)  th:not( )').each(function(i) {
                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table = $('#tbl_task').DataTable({
                language: {
                    oPaginate: {
                        sNext: '<i class="fa fa-forward"></i>',
                        sPrevious: '<i class="fa fa-backward"></i>',
                        sFirst: '<i class="fa fa-step-backward"></i>',
                        sLast: '<i class="fa fa-step-forward"></i>'
                    },
                },
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],
                "pageLength": 10,
                "pagingType": "full_numbers",
                "order": [3, "desc"],
                "columnDefs": [{ type: 'date', 'targets': [3] }],
                "scrollX": true,
                fixedColumns: {
                    left: 4
                },
                bSortCellsTop: true
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
            const tzone = "Asia/Manila";
            var shift_date = moment(response.data.data.shift_date).tz(tzone).format('YYYY-MM-DD');
            var date_received = moment(response.data.data.date_received).tz(tzone).format('YYYY-MM-DD');
            var start_date = moment(response.data.data.start_date).tz(tzone).format('MM/DD/YYYY hh:mm:ss a');
            var end_date = response.data.data.end_date ? moment(response.data.data.end_date).tz(tzone).format('MM/DD/YYYY hh:mm:ss a') : '';
            var allow_volume = response.data.data.status == 'Completed' ? false : true;
            var allow_remarks = response.data.data.status == 'Completed' ? false : true;

            $('#shift_date_edit').val(shift_date);
            $('#date_received_edit').val(date_received);
            $("#client_id_edit").val(response.data.data.client_id).trigger("change");
            $("#client_activity_id_edit").val(response.data.data.client_activity_id).trigger("change");
            $('#description_edit').text(response.data.data.description);
            $('#status_edit').val(response.data.data.status);
            $('#start_date_edit').val(start_date);
            $('#end_date_edit').val(end_date);
            $('#actual_handling_time_edit').val(response.data.data.actual_handling_time);
            $('#volume_edit').attr('readonly', allow_volume);
            $('#volume_edit').val(response.data.data.volume);
            $('#remarks_edit').attr('readonly', allow_remarks);
            $('#remarks_edit').text(response.data.data.remarks);
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
                        $('#volume_edit').attr('readonly', true);
                        $('#remarks_edit').attr('readonly', true);
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
            confirmButtonText: 'Yes, stop it!',
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
                    $('#btn_stop').append('<i class="fa fa-stop"></i> Stop');
                    $('#btn_stop').prop("disabled", false);
                }).catch(error => {
                    toastr.error(error);
                });
            }
        });
    });

    // show data to pause
    this_task.show_pause = (id) => {
        $('.error').hide();
        $('.error').text('');
        $('#pauseTaskModal').modal('show');
        _task_id = id
    }

    // pause
    $('#pauseTaskForm').on('submit', function(e) {
        e.preventDefault();
        id = _task_id;
        var formdata = new FormData(this);
        $('.error').hide();
        $('.error').text('');
        $('#btn_pause').empty();
        $('#btn_pause').append('<i class="fa fa-spinner fa-spin"></i> Pausing...');
        $('#btn_pause').prop("disabled", true);
        // Send a POST request
        axios({
            method: 'post',
            url: `${APP_URL}/my-task/pause/${id}`,
            data: formdata
        }).then(function(response) {
            console.log(response.data.status)
            if (response.data.status === 'success') {
                $('#loader').show();
                $("#tbl_task > tbody").empty();
                $("#tbl_task_info").hide();
                $("#tbl_task_paginate").hide();
                $('#pauseTaskForm')[0].reset();
                $('#create_button').load(' #create_button');
                $('.error').hide();
                $('.error').text('');
                TASK.load();
                $('#pauseTaskModal').modal('hide');
                toastr.success(response.data.message);
            } else if (response.data.status === 'warning') {
                Object.keys(response.data.error).forEach((key) => {
                    $(`#${[key]}_pauseError`).show();
                    $(`#${[key]}_pauseError`).text(response.data.error[key][0]);
                });
            } else {
                toastr.error(response.data.message);
            }
            $('#btn_pause').empty();
            $('#btn_pause').append('<i class="fa fa-pause"></i> Pause');
            $('#btn_pause').prop("disabled", false);
        }).catch(error => {
            toastr.error(error);
        });
    });

    // show data to resume
    this_task.show_resume = (id) => {
        $('.error').hide();
        $('.error').text('');
        $('#resumeTaskModal').modal('show');
        _task_id = id
    }

    // resume
    $('#resumeTaskForm').on('submit', function(e) {
        e.preventDefault();
        id = _task_id;
        var formdata = new FormData(this);
        $('.error').hide();
        $('.error').text('');
        $('#btn_resume').empty();
        $('#btn_resume').append('<i class="fa fa-spinner fa-spin"></i> Resuming...');
        $('#btn_resume').prop("disabled", true);
        // Send a POST request
        axios({
            method: 'post',
            url: `${APP_URL}/my-task/resume/${id}`,
            data: formdata
        }).then(function(response) {
            console.log(response.data.status)
            if (response.data.status === 'success') {
                $('#loader').show();
                $("#tbl_task > tbody").empty();
                $("#tbl_task_info").hide();
                $("#tbl_task_paginate").hide();
                $('#resumeTaskForm')[0].reset();
                $('#create_button').load(' #create_button');
                $('.error').hide();
                $('.error').text('');
                TASK.load();
                $('#resumeTaskModal').modal('hide');
                toastr.success(response.data.message);
            } else if (response.data.status === 'warning') {
                Object.keys(response.data.error).forEach((key) => {
                    $(`#${[key]}_resumeError`).show();
                    $(`#${[key]}_resumeError`).text(response.data.error[key][0]);
                });
            } else {
                toastr.error(response.data.message);
            }
            $('#btn_resume').empty();
            $('#btn_resume').append('<i class="fa fa-play"></i> Resume');
            $('#btn_resume').prop("disabled", false);
        }).catch(error => {
            toastr.error(error);
        });
    });

    return this_task;
})()
