{{-- <div class="row"> --}}
    <div class="col-lg-12 col-xl-8">
        <div class="card">
            <div class="card-body">
                {{-- <div class="table-responsive"> --}}
                    {{-- <button type="button" class="btn btn-success btn-sm mb-2" id="btn_export" data-toggle="tooltip" title="Click to download" tabindex="-1"><span class="bx bxs-download"></span> Export</button> --}}
                    {{-- <button type="button" class="btn btn-danger btn-sm mb-2" id="btn_exportPDF" data-toggle="tooltip" title="Click to download" tabindex="-1"><span class="bx bxs-download"></span> PDF</button> --}}
                    {{-- <h5 class="float-end" id="cluster">{{ ucwords(auth()->user()->thepermisssion->thecluster->name) }}</h5> --}}

                    {{-- <div class="buttons"><button class="btn btn-success btn-sm btn_hide float-end ihide" id="1"><i class="fa fa-eye-slash"></i> &nbsp;Hide</button></div> --}}
                    <div class="clearfix mb-2 ihide"></div>
                    <div class="div_daily">
                        <table id="tbl_daily_agent_fte" class="table table-bordered table-striped table-sm nowrap w-100 hideTable1">
                            <thead>
                                <tr>
                                    <th class="text-center">DAILY</th>
                                    <th colspan="6" class="text-center bg-secondary"><span class="daily_filter"></span></th>
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
                            <tbody id="task-data-daily">
                            </tbody>
                        </table>
                    </div>

                    {{-- <div class="buttons"><button class="btn btn-success btn-sm btn_hide float-end ihide" id="2"><i class="fa fa-eye-slash"></i> &nbsp;Hide</button></div>
                    <div class="clearfix mb-2 ihide"></div>
                    <div class="div_weekly">
                        <table id="tbl_weekly" class="table table-bordered table-striped table-sm nowrap w-100 hideTable2">
                            <thead>
                                <tr>
                                    <th class="text-center">WEEKLY</th>
                                    <th colspan="6" class="text-center bg-secondary"><span id="weekly_filter"></span></th>
                                </tr>
                                <tr class="task-header">
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Sum of Volume</th>
                                    <th class="text-center">Sum of AHT(in Mins)</th>
                                    <th class="text-center">Employee Number</th>
                                    <th class="text-center">Workdays</th>
                                    <th class="text-center">Work Minutes</th>
                                    <th class="text-center">RU %</th>
                                </tr>
                            </thead>
                            <tbody id="task-data-weekly">
                            </tbody>
                        </table>
                    </div>

                    <div class="buttons"><button class="btn btn-success btn-sm btn_hide float-end ihide" id="3"><i class="fa fa-eye-slash"></i> &nbsp;Hide</button></div>
                    <div class="clearfix mb-2 ihide"></div>
                    <div class="div_monthly">
                        <table id="tbl_monthly" class="table table-bordered table-striped table-sm nowrap w-100 hideTable3">
                            <thead>
                                <tr>
                                    <th class="text-center">MONTHLY</th>
                                    <th colspan="6" class="text-center bg-secondary"><span id="monthly_filter"></span></th>
                                </tr>
                                <tr class="task-header">
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Sum of Volume</th>
                                    <th class="text-center">Sum of AHT(in Mins)</th>
                                    <th class="text-center">Employee Number</th>
                                    <th class="text-center">Workdays</th>
                                    <th class="text-center">Work Minutes</th>
                                    <th class="text-center">RU %</th>
                                </tr>
                            </thead>
                            <tbody id="task-data-monthly">
                            </tbody>
                        </table>
                    </div> --}}

                    {{-- <div class="buttons"><button class="btn btn-success btn-sm btn_hide float-end ihide" id="4"><i class="fa fa-eye-slash"></i> &nbsp;Hide</button></div>
                    <div class="clearfix mb-2 ihide"></div>
                    <div class="div_yearly">
                        <table id="tbl_yearly" class="table table-bordered table-striped table-sm nowrap w-100 hideTable4">
                            <thead>
                                <tr>
                                    <th class="text-center">YEARLY</th>
                                    <th colspan="6" class="text-center bg-secondary"><span id="yearly_filter"></span></th>
                                </tr>
                                <tr class="task-header">
                                    <th class="text-center">Employee Name</th>
                                    <th class="text-center">Sum of Volume</th>
                                    <th class="text-center">Sum of AHT(in Mins)</th>
                                    <th class="text-center">Employee Number</th>
                                    <th class="text-center">Workdays</th>
                                    <th class="text-center">Work Minutes</th>
                                    <th class="text-center">RU %</th>
                                </tr>
                            </thead>
                            <tbody id="task-data-yearly">
                            </tbody>
                        </table>
                    </div> --}}
                {{-- </div> --}}
            </div>
        </div>
    </div>
{{-- </div> --}}
