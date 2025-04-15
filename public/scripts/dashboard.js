$(document).ready(function() {
    // $('#div_filter').hide();
    DASHBOARD.loadData($('#slct_filter').val(), 'daily');
    // DASHBOARD.loadDaily($('#slct_filter').val(), 'all');
    // DASHBOARD.loadWeekly($('#slct_filter').val(), 'all');
    // DASHBOARD.loadMonthly($('#slct_filter').val(), 'all');
    // DASHBOARD.loadYearly($('#slct_filter').val(), 'all');

});

const DASHBOARD = (() => {
    let this_dashboard = {}
    $('#slct_filter').on('change', function() {
        var select = $(this).val();
        var filterDiv = $('#div_filter');

        filterDiv.empty().hide();

        if (select !== 'all') {
            filterDiv.show();
            var inputHtml = '';
            switch (select) {
                case 'daily':
                    inputHtml = '<input type="date" class="form-control mt-2" id="filter_option" required />';
                    break;
                case 'weekly':
                    inputHtml = '<input type="week" class="form-control mt-2" id="filter_option" required />';
                    break;
                case 'monthly':
                    inputHtml = '<input type="month" class="form-control mt-2" id="filter_option" required />';
                    break;
                case 'yearly':
                    inputHtml = '<input type="number" placeholder="YYYY" min="2025" max="4000" class="form-control mt-2" id="filter_option" required />';
                    break;
                case 'quarterly':
                    inputHtml = `
                    <div class="d-flex mt-2">
                        <select class="form-control me-2" id="filter_option_quarter" required>
                            <option value="" selected disabled>-- QTR --</option>
                            <option value="Q1">Q1</option>
                            <option value="Q2">Q2</option>
                            <option value="Q3">Q3</option>
                            <option value="Q4">Q4</option>
                        </select>
                        <input type="number" placeholder="Year" min="2025" max="4000" class="form-control" id="filter_option_year" required>
                    </div>`;
            }
            filterDiv.append(inputHtml);
        }
    })

    $('#btn_filter').on('click', function() {
        var filterValue = $('#filter_option').val();
        if (filterValue !== "") {
            $('#btn_filter').html('<i class="fa fa-spinner fa-spin"></i>').prop("disabled", true);

            var select = $('#slct_filter').val();

            // if (select === 'all') {
            //     this_dashboard.loadDaily(data, select);
            //     this_dashboard.loadWeekly(data, select);
            //     this_dashboard.loadMonthly(data, select);
            //     this_dashboard.loadYearly(data, select);
            // } else {
            //     switch (select) {
            //         case 'daily':
            //             this_dashboard.loadDaily(data, select);
            //             break;
            //         case 'weekly':
            //             this_dashboard.loadWeekly(data, select);
            //             break;
            //         case 'monthly':
            //             this_dashboard.loadMonthly(data, select);
            //             break;
            //         case 'yearly':
            //             this_dashboard.loadYearly(data, select);
            //             break;
            //     }
            // }

            this_dashboard.loadData(filterValue, select);
        } else {
            toastr.warning("Do not leave blank");
        }
    });

    this_dashboard.loadData = (data, select) => {
        var filterValue = $('#filter_option').val();
        var datas = {
            filter: select,
            date: filterValue,
        }
        $('.div_filtered_by').addClass('card-loading');
        console.log(filterValue);
        axios({
            method: 'post',
            url: `${APP_URL}/dashboard/report/` + select,
            data: datas
        }).then(function(response) {
            // agents FTE
            $('#tbl_agent_fte').DataTable().destroy();
            var table;
            console.log(response.data.data)
            const data = response.data.data;

            $('.date_filter').text(data.date);
            data.agents_fte.forEach(val => {
                table +=
                    `<tr>
                        <td>${val.client}</td>
                        <td>${val.employee_name}</td>
                        <td class="text-center">${val.sum_volume}</td>
                        <td class="text-center">${val.sum_aht}</td>
                        <td class="text-center">${val.workdays}</td>
                        <td class="text-center">${val.work_minutes}</td>
                        <td class="text-center">${val.ru_percent}</td>
                    </tr>`;
            });

            $('#tbl_agent_fte tbody').html(table)
            $('#tbl_agent_fte').DataTable({
                language: {
                    oPaginate: {
                        sNext: '<i class="fa fa-forward"></i>',
                        sPrevious: '<i class="fa fa-backward"></i>',
                        sFirst: '<i class="fa fa-step-backward"></i>',
                        sLast: '<i class="fa fa-step-forward"></i>'
                    },
                },
                pagingType: "full_numbers",
                pageLength: 20,
                lengthMenu: [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                scrollX: true,
            });

            // clients FTE
            $('#tbl_client_fte').DataTable().destroy();
            var table_client_fte;
            let totalRU = 0;
            let count = 0;

            data.clients_fte.forEach(val => {
                table_client_fte +=
                    `<tr>
                        <td>${val.client}</td>
                        <td class="text-center">${val.average_ru}</td>
                    </tr>`;
                totalRU += parseFloat(val.average_ru);
                count++;
            });

            $('#tbl_client_fte tbody').html(table_client_fte);

            let overall_average_ru = (count > 0) ? (totalRU / count).toFixed(2) + '%' : '0%';
            $('#overall_avg_ru').text(overall_average_ru);

            $('#tbl_client_fte').DataTable({
                language: {
                    oPaginate: {
                        sNext: '<i class="fa fa-forward"></i>',
                        sPrevious: '<i class="fa fa-backward"></i>',
                        sFirst: '<i class="fa fa-step-backward"></i>',
                        sLast: '<i class="fa fa-step-forward"></i>'
                    },
                },
                pagingType: "full_numbers",
                pageLength: 20,
                lengthMenu: [
                    [10, 20, 50, -1],
                    [10, 20, 50, "All"]
                ],
                scrollX: true,
            });

            $('.div_filtered_by').removeClass('card-loading');

            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i>');
            $('#btn_filter').prop("disabled", false);


        }).catch(error => {
            toastr.error(error);
        });
    }

    $('#btn_export').on('click', function() {
        let cluster = $('#cluster').text();
        let wb = XLSX.utils.book_new();
        let allData = [];

        // Capture plain text data from all tables
        $('table').each(function() {
            $(this).find('tr').each(function() {
                allData.push($(this).find('th, td').map(function() {
                    return $(this).text().trim();
                }).get());
            });
            allData.push([]); // Add empty row between tables
        });

        let ws = XLSX.utils.aoa_to_sheet(allData);
        XLSX.utils.book_append_sheet(wb, ws, `${cluster}`);
        XLSX.writeFile(wb, `${cluster}.xlsx`);
    })

    $('#btn_exportPDF').on('click', async function() {
        let cluster = $('#cluster').text();
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        const pageHeight = 277;
        let yOffset = 10;

        for (const table of $('table')) {
            let canvas = await html2canvas(table, { scale: 2, useCORS: true });
            let imgData = canvas.toDataURL('image/png');
            let imgWidth = 190;
            let imgHeight = (canvas.height * imgWidth) / canvas.width;

            if (yOffset + imgHeight > pageHeight) {
                doc.addPage();
                yOffset = 10;
            }

            doc.addImage(imgData, 'PNG', 10, yOffset, imgWidth, imgHeight);
            yOffset += imgHeight + 10;
        }

        doc.save(`${cluster}.pdf`);
    });

    $('.btn_hide').on('click', function() {
        const table = $('.hideTable' + this.id);
        const button = $(this);

        table.fadeToggle(500, function() {
            if (table.is(':visible')) {
                button.html('<i class="fa fa-eye-slash"></i> &nbsp;Hide');
            } else {
                button.html('<i class="fa fa-eye"></i> Show');
            }
        });
    });


    return this_dashboard;
})()