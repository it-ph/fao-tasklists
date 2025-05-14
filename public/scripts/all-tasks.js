$(document).ready(function() {
    TASK.load();
});

const TASK = (() => {
    const isAdmin = $('#permission').text();
    const orderTable = isAdmin ? 4 : 3;
    let this_task = {}

    // load data
    this_task.load = () => {
        var filter_status = $('#status').text().trimStart();
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
            order: [orderTable, "desc"],
            columnDefs: [{ type: 'date', 'targets': [orderTable] }],
            processing: true,
            serverSide: true,
            ajax: {
                url: `${APP_URL}/tasks/api/` + filter_status,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            },
            columns: [
                { data: 'id', name: 'id', className: 'text-center' },
                { data: 'status', name: 'status', className: 'text-center' },
                ...(isAdmin ? [{ data: 'action', name: 'action', className: 'text-center' }] : []),
                { data: 'agent_id', name: 'theagent.fullname' },
                { data: 'shift_date', name: 'shift_date', className: 'text-center' },
                { data: 'date_received', name: 'date_received', className: 'text-center' },
                { data: 'thecluster.name', name: 'thecluster.name' },
                { data: 'theclient.name', name: 'theclient.name' },
                { data: 'theclientactivity.name', name: 'theclientactivity.name' },
                { data: 'theclientactivity.function', name: 'theclientactivity.function' },
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
        $('#editTaskForm')[0].reset();
        $("#cluster_id_edit").val(null).trigger("change");
        $("#client_id_edit").val(null).trigger("change");
        $("#client_activity_id_edit").val(null).trigger("change");
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
            var start_date = moment(response.data.data.start_date).tz(tzone).format('YYYY-MM-DDTHH:mm');
            var end_date = response.data.data.end_date ? moment(response.data.data.end_date).tz(tzone).format('YYYY-MM-DDTHH:mm') : '';
            var allow_volume = response.data.data.status == 'Completed' ? false : true;
            var allow_remarks = response.data.data.status == 'Completed' ? false : true;

            $('#agent_id').val(response.data.data.agent_id);
            $('#employee_name').val(response.data.data.theagent.fullname);
            $('#shift_date_edit').val(shift_date);
            $('#date_received_edit').val(date_received);
            $("#cluster_id_edit").val(response.data.data.cluster_id).trigger("change");
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

    return this_task;
})()
