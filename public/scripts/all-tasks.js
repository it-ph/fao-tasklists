$(document).ready(function() {
    TASK.load();
});

const TASK = (() => {
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
            order: [3, "desc"],
            columnDefs: [{ type: 'date', 'targets': [3] }],
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
                { data: 'status', name: 'status', className: 'text-center' },
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

    return this_task;
})()