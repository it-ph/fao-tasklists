    {{-- AGENTS FTE --}}
    <div class="col-lg-12 col-xl-8">
        <div class="card">
            <div class="card-body">
                <h5 class="float-end ihide" id="cluster">{{ ucwords(auth()->user()->thepermisssion->thecluster->name) }}</h5>
                <div class="clearfix mb-2 ihide"></div>
                <div class="div_filtered_by">
                    <table id="tbl_agent_fte" class="table table-bordered table-striped table-sm nowrap w-100">
                        <thead>
                            <tr>
                                {{-- <th class="text-center filtered_by"></th> --}}
                                <th colspan="7" class="text-center bg-secondary"><span class="date_filter"></span></th>
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
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- CLIENTS FTE --}}
    <div class="col-lg-12 col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="clearfix mb-2 ihide"></div>
                <div class="div_filtered_by">
                    <table id="tbl_client_fte" class="table table-bordered table-striped table-sm nowrap w-100">
                        <thead>
                            <tr>
                                {{-- <th class="text-center filtered_by"></th> --}}
                                <th colspan="2" class="text-center bg-secondary"><span class="date_filter"></span></th>
                            </tr>
                            <tr class="task-header">
                                <th class="text-center col-2">Client Name</th>
                                <th class="text-center col-2">Average of RU %</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td class="fw-bold">Grand Total</td>
                                <td id="overall_avg_ru" class="text-center fw-bold"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
