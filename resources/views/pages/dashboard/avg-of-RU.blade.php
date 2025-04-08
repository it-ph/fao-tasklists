
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                {{-- <div class="table-responsive"> --}}
                    {{-- <button type="button" class="btn btn-success btn-sm mb-2" id="btn_export" data-toggle="tooltip" title="Click to download" tabindex="-1"><span class="bx bxs-download"></span> Export</button> --}}
                    {{-- <button type="button" class="btn btn-danger btn-sm mb-2" id="btn_exportPDF" data-toggle="tooltip" title="Click to download" tabindex="-1"><span class="bx bxs-download"></span> PDF</button> --}}
                    {{-- <h5 class="float-end" id="cluster">{{ ucwords(auth()->user()->thepermisssion->thecluster->name) }}</h5> --}}

                    {{-- <div class="buttons"><button class="btn btn-success btn-sm btn_hide float-end ihide" id="1"><i class="fa fa-eye-slash"></i> &nbsp;Hide</button></div> --}}
                    <div class="clearfix mb-2 ihide"></div>
                    <div class="div_daily">
                        <table id="tbl_avg_ru" class="table table-bordered table-striped table-sm nowrap w-100 hideTable1">
                            <thead>
                                {{-- <tr>
                                    <th class="text-center">DAILY</th>
                                    <th colspan="2" class="text-center bg-secondary"><span id="daily_filter"></span></th>
                                </tr> --}}
                                <tr class="task-header">
                                    <th class="text-center col-3">Client</th>
                                    <th class="text-center col-1">Average of RU %</th>
                                </tr>
                            </thead>
                            <tbody id="task-data-daily">
                            </tbody>
                        </table>
                    </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
