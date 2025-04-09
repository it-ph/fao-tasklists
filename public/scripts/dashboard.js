$(document).ready(function() {
    $('#div_filter').hide();
    DASHBOARD.loadDaily($('#slct_filter').val(), 'all');
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
                            inputHtml = '<input type="date" class="form-control" id="filter_option" required />';
                            break;
                        case 'weekly':
                            inputHtml = '<input type="week" class="form-control" id="filter_option" required />';
                            break;
                        case 'monthly':
                            inputHtml = '<input type="month" class="form-control" id="filter_option" required />';
                            break;
                        case 'yearly':
                            inputHtml = '<input type="number" placeholder="YYYY" min="2025" max="4000" class="form-control" id="filter_option" required />';
                            break;
                    }
                    filterDiv.append(inputHtml);
                }
            })

            $('#btn_filter').on('click', function() {
                var filterValue = $('#filter_option').val();
                if (filterValue !== "") {
                    $('#btn_filter').html('<i class="fa fa-spinner fa-spin"></i> Loading...').prop("disabled", true);

                    var select = $('#slct_filter').val();
                    var data = filterValue;

                    if (select === 'all') {
                        this_dashboard.loadDaily(data, select);
                        // this_dashboard.loadWeekly(data, select);
                        // this_dashboard.loadMonthly(data, select);
                        // this_dashboard.loadYearly(data, select);
                    } else {
                        switch (select) {
                            case 'daily':
                                this_dashboard.loadDaily(data, select);
                                break;
                            case 'weekly':
                                this_dashboard.loadWeekly(data, select);
                                break;
                            case 'monthly':
                                this_dashboard.loadMonthly(data, select);
                                break;
                            case 'yearly':
                                this_dashboard.loadYearly(data, select);
                                break;
                        }
                    }
                } else {
                    toastr.warning("Do not leave blank");
                }
            });

            this_dashboard.loadDaily = (data, select) => {
                var datas = {
                    filter: select,
                    date: data,
                }
                $('.div_daily').addClass('card-loading');
                $('#1').html('<i class="fa fa-eye-slash"></i> &nbsp;Hide');
                axios({
                    method: 'post',
                    url: `${APP_URL}/dashboard/report/daily`,
                    data: datas
                }).then(function(response) {
                    // agents FTE
                    $('#tbl_daily_agent_fte').DataTable().destroy();
                    var table;
                    console.log(response.data.data)
                    const data = response.data.data;

                    $('.daily_filter').text(data.date);
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

                    $('#tbl_daily_agent_fte tbody').html(table)
                    $('#tbl_daily_agent_fte').DataTable({
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
                    $('#tbl_daily_client_fte').DataTable().destroy();
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

                    $('#tbl_daily_client_fte tbody').html(table_client_fte);

                    let overall_average_ru = (count > 0) ? (totalRU / count).toFixed(2) + '%' : '0%';
                    $('#overall_avg_ru').text(overall_average_ru);

                    $('#tbl_daily_client_fte').DataTable({
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

                    $('.div_daily').removeClass('card-loading');

                    $('#btn_filter').empty();
                    $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
                    $('#btn_filter').prop("disabled", false);


                }).catch(error => {
                    toastr.error(error);
                });
            }





            this_dashboard.loadWeekly = (data, select) => {
                    var datas = {
                        filter: select,
                        date: data,
                    }
                    $('.div_weekly').addClass('card-loading');
                    $('#tbl_weekly').show();
                    $('#2').html('<i class="fa fa-eye-slash"></i> &nbsp;Hide');
                    axios({
                            method: 'post',
                            url: `${APP_URL}/dashboard/report/weekly`,
                            data: datas
                        }).then(function(response) {
                                $("#tbl_weekly tbody").empty();
                                if (response.data.status === 'success') {
                                    console.log(response.data.data);
                                    const data = response.data.data;

                                    const $thead = $('.task-header');
                                    const $tbody = $('#task-data-weekly');
                                    $thead.find('th:gt(0)').remove();
                                    $tbody.empty();

                                    // Add activity headers
                                    data.cluster_activities.forEach(activity => {
                                        $thead.append(`<th class="text-center">${activity.name}</th>`);
                                    });

                                    $thead.append('<th class="text-center">AVE. AHT</th>');
                                    $thead.append('<th class="text-center">Working Hours</th>');

                                    $('#weekly_filter').text(data.date);
                                    $('.activity-count').attr('colspan', data.cluster_activities.length + 2);

                                    // Add agent rows
                                    data.agents.forEach(agent => {
                                        let taskColumns = '';

                                        data.cluster_activities.forEach(activity => {
                                            taskColumns += `<td class="text-center">${data.agentTaskCounts[agent.id]?.[activity.id] ?? 0}</td>`;
                                        });

                                        const row = `
                                <tr>
                                    <td>${agent.fullname}</td>
                                    ${taskColumns}
                                    <td class="text-center">${data.avgAHT[agent.id] ?? '00:00:00'}</td>
                                    <td class="text-center">${data.totalWorkingHours[agent.id] ?? '00:00:00'}</td>
                                </tr>
                            `;
                                        $tbody.append(row);
                                    });

                                    // Total row
                                    const totalRow = `
                            <tr class="text-success fw-bold">
                                <td class="text-center">Total</td>
                                ${data.cluster_activities.map(activity => `<td class="text-center">${data.totalTaskCounts[activity.id] ?? 0}</td>`).join('')}
                                <td class="text-center">${data.total_avgAHT}</td>
                                <td class="text-center">${data.total_avgWorkingHours}</td>
                            </tr>
                        `;
                $tbody.append(totalRow);
                $('.div_weekly').removeClass('card-loading');

            }
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);


        }).catch(error => {
            toastr.error(error);
        });
    }

    this_dashboard.loadMonthly = (data, select) => {
        var datas = {
            filter: select,
            date: data,
        }
        $('.div_monthly').addClass('card-loading');
        $('#tbl_monthly').show();
        $('#3').html('<i class="fa fa-eye-slash"></i> &nbsp;Hide');
        axios({
            method: 'post',
            url: `${APP_URL}/dashboard/report/monthly`,
            data: datas
        }).then(function (response) {
            $("#tbl_monthly tbody").empty();
            if (response.data.status === 'success') {
                console.log(response.data.data);
                const data = response.data.data;

                const $thead = $('.task-header');
                const $tbody = $('#task-data-monthly');
                $thead.find('th:gt(0)').remove();
                $tbody.empty();

                // Add activity headers
                data.cluster_activities.forEach(activity => {
                    $thead.append(`<th class="text-center">${activity.name}</th>`);
                });

                $thead.append('<th class="text-center">AVE. AHT</th>');
                $thead.append('<th class="text-center">Working Hours</th>');

                $('#monthly_filter').text(data.date);
                $('.activity-count').attr('colspan', data.cluster_activities.length + 2);

                // Add agent rows
                data.agents.forEach(agent => {
                    let taskColumns = '';

                    data.cluster_activities.forEach(activity => {
                        taskColumns += `<td class="text-center">${data.agentTaskCounts[agent.id]?.[activity.id] ?? 0}</td>`;
                    });

                    const row = `
                                <tr>
                                    <td>${agent.fullname}</td>
                                    ${taskColumns}
                                    <td class="text-center">${data.avgAHT[agent.id] ?? '00:00:00'}</td>
                                    <td class="text-center">${data.totalWorkingHours[agent.id] ?? '00:00:00'}</td>
                                </tr>
                            `;
                    $tbody.append(row);
                });

                // Total row
                const totalRow = `
                            <tr class="text-success fw-bold">
                                <td class="text-center">Total</td>
                                ${data.cluster_activities.map(activity => `<td class="text-center">${data.totalTaskCounts[activity.id] ?? 0}</td>`).join('')}
                                <td class="text-center">${data.total_avgAHT}</td>
                                <td class="text-center">${data.total_avgWorkingHours}</td>
                            </tr>
                        `;
                $tbody.append(totalRow);
                $('.div_monthly').removeClass('card-loading');

            }
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);


        }).catch(error => {
            toastr.error(error);
        });
    }

    this_dashboard.loadYearly = (data, select) => {
        var datas = {
            filter: select,
            date: data,
        }
        $('.div_yearly').addClass('card-loading');
        $('#tbl_yearly').show();
        $('#4').html('<i class="fa fa-eye-slash"></i> &nbsp;Hide');
        axios({
            method: 'post',
            url: `${APP_URL}/dashboard/report/yearly`,
            data: datas
        }).then(function (response) {
            $("#tbl_yearly tbody").empty();
            if (response.data.status === 'success') {
                console.log(response.data.data);
                const data = response.data.data;

                const $thead = $('.task-header');
                const $tbody = $('#task-data-yearly');
                $thead.find('th:gt(0)').remove();
                $tbody.empty();

                // Add activity headers
                data.cluster_activities.forEach(activity => {
                    $thead.append(`<th class="text-center">${activity.name}</th>`);
                });

                $thead.append('<th class="text-center">AVE. AHT</th>');
                $thead.append('<th class="text-center">Working Hours</th>');

                $('#yearly_filter').text(data.date);
                $('.activity-count').attr('colspan', data.cluster_activities.length + 2);

                // Add agent rows
                data.agents.forEach(agent => {
                    let taskColumns = '';

                    data.cluster_activities.forEach(activity => {
                        taskColumns += `<td class="text-center">${data.agentTaskCounts[agent.id]?.[activity.id] ?? 0}</td>`;
                    });

                    const row = `
                                <tr>
                                    <td>${agent.fullname}</td>
                                    ${taskColumns}
                                    <td class="text-center">${data.avgAHT[agent.id] ?? '00:00:00'}</td>
                                    <td class="text-center">${data.totalWorkingHours[agent.id] ?? '00:00:00'}</td>
                                </tr>
                            `;
                    $tbody.append(row);
                });

                // Total row
                const totalRow = `
                            <tr class="text-success fw-bold">
                                <td class="text-center">Total</td>
                                ${data.cluster_activities.map(activity => `<td class="text-center">${data.totalTaskCounts[activity.id] ?? 0}</td>`).join('')}
                                <td class="text-center">${data.total_avgAHT}</td>
                                <td class="text-center">${data.total_avgWorkingHours}</td>
                            </tr>
                        `;
                $tbody.append(totalRow);
                $('.div_yearly').removeClass('card-loading');

            }
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);


        }).catch(error => {
            toastr.error(error);
        });
    }

    $('#btn_export').on('click', function () {
        let cluster = $('#cluster').text();
        let wb = XLSX.utils.book_new();
        let allData = [];

        // Capture plain text data from all tables
        $('table').each(function () {
            $(this).find('tr').each(function () {
                allData.push($(this).find('th, td').map(function () {
                    return $(this).text().trim();
                }).get());
            });
            allData.push([]); // Add empty row between tables
        });

        let ws = XLSX.utils.aoa_to_sheet(allData);
        XLSX.utils.book_append_sheet(wb, ws, `${cluster}`);
        XLSX.writeFile(wb, `${cluster}.xlsx`);
    })

    $('#btn_exportPDF').on('click', async function () {
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

    $('.btn_hide').on('click', function () {
        const table = $('.hideTable' + this.id);
        const button = $(this);

        table.fadeToggle(500, function () {
            if (table.is(':visible')) {
                button.html('<i class="fa fa-eye-slash"></i> &nbsp;Hide');
            } else {
                button.html('<i class="fa fa-eye"></i> Show');
            }
        });
    });


    return this_dashboard;
})()