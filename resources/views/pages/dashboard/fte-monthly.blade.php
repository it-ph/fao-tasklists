    <div class="col-lg-12 col-xl-8">
        <div class="card">
            <div class="card-body">
                {{-- <button type="button" class="btn btn-success btn-sm mb-2" id="btn_export" data-toggle="tooltip" title="Click to download" tabindex="-1"><span class="bx bxs-download"></span> Export</button> --}}
                {{-- <button type="button" class="btn btn-danger btn-sm mb-2" id="btn_exportPDF" data-toggle="tooltip" title="Click to download" tabindex="-1"><span class="bx bxs-download"></span> PDF</button> --}}
                {{-- <h5 class="float-end" id="cluster">{{ ucwords(auth()->user()->thepermisssion->thecluster->name) }}</h5> --}}

                {{-- <div class="buttons"><button class="btn btn-success btn-sm btn_hide float-end ihide" id="1"><i class="fa fa-eye-slash"></i> &nbsp;Hide</button></div> --}}
                <div class="clearfix mb-2 ihide"></div>
                <div class="div_monthly">
                    <table id="tbl_monthly_agent_fte" class="table table-bordered table-striped table-sm nowrap w-100 hideTable3">
                        <thead>
                            <tr>
                                <th class="text-center">MONTHLY</th>
                                <th colspan="6" class="text-center bg-secondary"><span class="monthly_filter"></span></th>
                            </tr>
                            <tr class="task-header">
                                <th class="text-center">Client</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Sum of Volume</th>
                                <th class="text-center">Sum of AHT(in Mins)</th>
                                <th class="text-center">Workdays</th>
                                <th class="text-center">Work Minutes</th>
                                <th class="text-center">RU %</th>
                            </tr>
                        </thead>
                        <tbody id="task-data-monthly">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-xl-4">
        <div class="card">
            <div class="card-body">
                {{-- <button type="button" class="btn btn-success btn-sm mb-2" id="btn_export" data-toggle="tooltip" title="Click to download" tabindex="-1"><span class="bx bxs-download"></span> Export</button> --}}
                {{-- <button type="button" class="btn btn-danger btn-sm mb-2" id="btn_exportPDF" data-toggle="tooltip" title="Click to download" tabindex="-1"><span class="bx bxs-download"></span> PDF</button> --}}
                {{-- <h5 class="float-end" id="cluster">{{ ucwords(auth()->user()->thepermisssion->thecluster->name) }} </h5> --}}

                {{-- <div class="buttons"><button class="btn btn-success btn-sm btn_hide float-end ihide" id="1"><i  class="fa fa-eye-slash"></i> &nbsp;Hide</button></div> --}}
                <div class="clearfix mb-2 ihide"></div>
                <div class="div_monthly">
                    <table id="tbl_monthly_client_fte"
                        class="table table-bordered table-striped table-sm nowrap w-100 hideTable3">
                        <thead>
                            {{-- <tr>
                                <th class="text-center">monthly</th>
                                <th colspan="2" class="text-center bg-secondary"><span class="monthly_filter">&nbsp;</span></th>
                            </tr> --}}
                            <tr class="task-header">
                                <th class="text-center col-2">Client Name</th>
                                <th class="text-center col-2">Average of RU %</th>
                            </tr>
                        </thead>
                        <tbody id="task-data-monthly">
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="fw-bold">Grand Total</td>
                                <td id="monthly_overall_avg_ru" class="text-center fw-bold"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
