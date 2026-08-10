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
            order: [4, "desc"],
            columnDefs: [{ type: 'date', 'targets': [4] }],
            processing: true,
            serverSide: true,
            ajax: {
                url: `${APP_URL}/task-assignments/api/` + filter_status,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            },
            columns: [{
                    data: 'id',
                    name: 'id', // Must be 'id' so backend knows which column to search
                    className: 'text-center',
                    render: function(data, type, row) {
                        return 'TA' + data;
                    }
                },
                { data: 'status', name: 'status', className: 'text-center' },
                // ...(isAdmin ? [{ data: 'action', name: 'action', className: 'text-center' }] : []),
                { data: 'action', name: 'action', className: 'text-center' },
                { data: 'agent_id', name: 'theagent.fullname' },
                { data: 'schedule', name: 'schedule', className: 'text-center' },
                { data: 'thecluster.name', name: 'thecluster.name' },
                {
                    data: 'theclient.name',
                    name: 'theclient.name',
                    defaultContent: '-'
                },
                { data: 'activity_name', name: 'activity_name' },
                { data: 'applicable_month', name: 'applicable_month', className: 'text-center' },
                { data: 'client_function', name: 'client_function', className: 'text-center', defaultContent: '-' },
                { data: 'eclerx_function', name: 'eclerx_function', className: 'text-center' },
                { data: 'start_date', name: 'start_date', className: 'text-center' },
                { data: 'end_date', name: 'end_date', className: 'text-center' },
                { data: 'date_completed', name: 'date_completed', className: 'text-center' },
                { data: 'actual_handling_time', name: 'actual_handling_time', className: 'text-center' },
                { data: 'timeliness', name: 'timeliness', defaultContent: '-', className: 'text-center' },
                { data: 'quality', name: 'quality', className: 'text-center' },
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

    // task template
    $('#btn_export').on('click', function(e) {
        e.preventDefault();
        $('#btn_export').empty();
        $('#btn_export').append('<i class="fa fa-spinner fa-spin"></i> Exporting...');
        $('#btn_export').prop("disabled", true);
        toastr.info('Exporting Template...');

        $.ajax({
            url: `${APP_URL}/task-assignments/export/template`,
            method: 'GET',
            xhrFields: {
                responseType: 'blob' // This is important for file downloads
            },
            success: function(data, status, xhr) {
                var filename = ""; // This needs to be set, or derive it from response headers if needed
                var disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition) {
                    var matches = /filename="([^"]*)"/.exec(disposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1];
                    }
                }

                var url = window.URL.createObjectURL(data);
                var a = document.createElement('a');
                a.href = url;
                a.download = filename || 'user-upload-template.xlsx'; // Fallback filename
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);

                $('#btn_export').empty();
                $('#btn_export').append('<i class="fa fa-download"></i> Export');
                $('#btn_export').prop("disabled", false);
                $('#btn_search').prop("disabled", false);
                $('#btn_reset').prop("disabled", false);
            },
            error: function() {
                toastr.error('Export Failed!');
                $('#btn_export').empty();
                $('#btn_export').append('<i class="fa fa-download"></i> Export');
                $('#btn_export').prop("disabled", false);
                $('#btn_search').prop("disabled", false);
                $('#btn_reset').prop("disabled", false);
            }
        });
    });

    // show upload modal
    this_task.showUploadModal = () => {
        $('#uploadTaskAssignmentsModal').modal('show');
        resetButton();
        let errorList = $('#errorList');
        errorList.empty();
        errorList.hide();
    }

    // task import
    $('#btn_import').on('click', function(e) {
        e.preventDefault();

        var formData = new FormData();
        var fileInput = document.getElementById('import_file');
        let errorList = $('#errorList');
        errorList.empty();
        errorList.hide();

        if (fileInput.files.length === 0) {
            $('#import_fileError').show();
            toastr.error('Please select a file to upload.');
            return;
        }
        $('#import_fileError').hide();
        $('#btn_import').empty();
        $('#btn_import').append('<i class="fa fa-spinner fa-spin"></i> Uploading...');
        $('#btn_import').prop("disabled", true);
        toastr.info('Uploading Data...');


        formData.append('import_file', fileInput.files[0]);

        axios({
                method: 'POST',
                url: `${APP_URL}/task-assignments/import`,
                data: formData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                console.log(response.data);
                if (response.data.status === 'success') {
                    $('#uploadTaskAssignmentsModal').modal('hide');
                    toastr.success(response.data.message);
                    TASK.load();
                } else if (response.data.status === 'warning') {
                    toastr.error(response.data.message);
                    errorList.show();
                    Object.entries(response.data.error).forEach(([key, value]) => {
                        let li = $('<li></li>').text(value);
                        errorList.append(li);
                    });
                }
            })
            .catch(error => {
                console.error('An error occurred:', error);
                toastr.error(error);
            })
            .finally(() => {
                resetButton();
            });
    });

    function resetButton() {
        $('#import_fileError').hide();
        $('#btn_import').html('<i class="fa fa-save"></i> Upload');
        $('#btn_import').prop("disabled", false);
        $('#import_file').val('');
    }

    return this_task;
})()