$(document).ready(function() {
    TASK.load();
});

const TASK = (() => {
    let this_task = {}

    // load data
    this_task.load = () => {
        var filter_status = $('#status').html();
        axios(`${APP_URL}/task?status=` + filter_status).then(function(response) {
            $('#tbl_task').DataTable().destroy();
            var table;
            console.log(response.data.data)
            response.data.data.forEach(val => {
                table +=
                    `<tr>
                        <td>${val.status}</td>
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
                "order": [2, "desc"],
                "columnDefs": [{ type: 'date', 'targets': [2] }],
                "scrollX": true,
                fixedColumns: {
                    left: 3
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

    return this_task;
})()