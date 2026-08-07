$(document).ready(function() {
    LIVE_STATUS.load();

    // SILENT AUTO-REFRESH ENGINE: Re-queries database fields every 10 seconds 
    // without resetting your scroll position or triggering annoying browser reloads!
    setInterval(function() {
        if ($.fn.DataTable.isDataTable('#tbl_live_user_status')) {
            $('#tbl_live_user_status').DataTable().ajax.reload(null, false); // false keeps user on current page pagination
        }
    }, 10000);
});

const LIVE_STATUS = (() => {
    let this_live_status = {};
    let _user_id; // For handling individual user tracking actions later if needed

    // load data
    this_live_status.load = () => {
        $.fn.dataTable.ext.errMode = 'none';

        $('#tbl_live_user_status').DataTable().clear().draw();
        $('#tbl_live_user_status').DataTable().destroy();
        $('#tbl_live_user_status').DataTable({
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
            order: [0, "asc"], // Alphabetical by fullname column
            processing: true,
            serverSide: true,
            ajax: {
                url: `${APP_URL}/user/api/live-status`, // Ensure this maps to your new controller method
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            },
            columns: [
                { data: 'fullname', name: 'fullname' },
                { data: 'clock_in', name: 'theattendances.clock_in', className: 'text-center' },
                { data: 'clock_out', name: 'theattendances.clock_out', className: 'text-center' },
                { data: 'live_status', name: 'live_status', className: 'text-center', searchable: false },
                { data: 'work_status', name: 'work_status', className: 'text-center', searchable: false }
            ],
            dom: 'Blfrtip',
            buttons: [{
                extend: 'excel',
                text: '<i class="fa fa-download"></i> Export',
                exportOptions: {
                    columns: ':not(:last-child)' // Excludes the last column (Action) from Excel file exports
                }
            }],
        });

        $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
            console.log(message);
        };
    }

    // Modal action preview tracking handler placeholder matching your style
    this_live_status.showLogs = (id) => {
        _user_id = id;
        console.log("Viewing user logs for ID: " + _user_id);
    }

    return this_live_status;
})();