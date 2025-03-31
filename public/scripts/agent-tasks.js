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
                        $("#cluster_activity_id").val(null).trigger("change");
                        $('#create_button').load(' #create_button');
                        // $('#has_active_task').load(' #has_active_task');
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
        $.fn.dataTable.ext.errMode = 'none';
        $('#tbl_task').DataTable().clear().draw();
        $('#tbl_task').DataTable().destroy();
        $('#tbl_task').DataTable({
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
                [10, 20, 50, 100],
                [10, 20, 50, 100]
            ],
            order: [4, "desc"],
            columnDefs: [{ type: 'date', 'targets': [4] }],
            processing: true,
            serverSide: true,
            ajax: {
                url: `${APP_URL}/my-task/api/` + filter_status,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            },
            columns: [
                { data: 'status', name: 'status', className: 'text-center' },
                { data: 'action', name: 'action', className: 'text-center' },
                { data: 'theagent.fullname', name: 'theagent.fullname' },
                { data: 'shift_date', name: 'shift_date', className: 'text-center' },
                { data: 'date_received', name: 'date_received', className: 'text-center' },
                { data: 'thecluster.name', name: 'thecluster.name' },
                { data: 'theclient.name', name: 'theclient.name' },
                { data: 'theclientactivity.name', name: 'theclientactivity.name' },
                { data: 'description', name: 'description' },
                { data: 'start_date', name: 'start_date', className: 'text-center' },
                { data: 'end_date', name: 'end_date', className: 'text-center' },
                { data: 'date_completed', name: 'date_completed', className: 'text-center' },
                { data: 'actual_handling_time', name: 'actual_handling_time', className: 'text-center' },
                { data: 'volume', name: 'volume', className: 'text-center' },
                { data: 'remarks', name: 'remarks' },
            ],
        });
        $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
            console.log(message);
        };
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
            $("#cluster_activity_id_edit").val(response.data.data.cluster_activity_id).trigger("change");
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
                        $("#cluster_activity_id_edit").val(null).trigger("change");
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
                        // $('#has_active_task').load(' #has_active_task');
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
                // $('#has_active_task').load(' #has_active_task');
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
        // var has_active_task = $('#has_active_task').text();
        // has_active_task ? TASK.has_active_task() : $('#resumeTaskModal').modal('show');

        $('.error').hide();
        $('.error').text('');
        $('#btn-resume-' + id).empty();
        $('#btn-resume-' + id).append('<i class="fa fa-spinner fa-spin"></i>');
        $('#btn-resume-' + id).prop("disabled", true);
        axios({
            method: 'post',
            url: `${APP_URL}/my-task/has-active-task`,
        }).then(function(response) {
            console.log(response.data)
            if (response.data.status === 'success') {
                var has_active_task = response.data.data;
                has_active_task ? TASK.has_active_task() : $('#resumeTaskModal').modal('show');
            } else {
                toastr.error(response.data.message);
            }
            $('#btn-resume-' + id).empty();
            $('#btn-resume-' + id).append('<i class="fa fa-play"></i>');
            $('#btn-resume-' + id).prop("disabled", false);
        }).catch(error => {
            toastr.error(error);
        });

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
                // $('#has_active_task').load(' #has_active_task');
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

    this_task.has_active_task = () => {
        Swal.fire({
            title: 'Invalid Action',
            text: "Please On Hold or Complete your current task before creating a new one!",
            icon: 'error',
            confirmButtonText: 'Okay!',
            confirmButtonClass: 'btn btn-primary mt-2',
            buttonsStyling: false,
            allowOutsideClick: false
        });
    }

    return this_task;
})()