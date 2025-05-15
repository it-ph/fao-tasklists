
    <div class="col-lg-4 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body">
                <div class="input-group">
                    <select class="form-control mt-2 me-2" name="slct_filter" id="slct_filter">
                        {{-- <option value="all">All</option> --}}
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        {{-- <option value="yearly">Yearly</option> --}}
                        {{-- <option value="quarterly">Quarterly</option> --}}
                    </select>
                    <div id="div_filter">
                        <input type="date" class="form-control mt-2" id="filter_option" value="{{ auth()->user()->shift_date ? date('Y-m-d', strtotime(auth()->user()->shift_date)) : date('Y-m-d') }}" required />
                    </div>
                    <span class="input-group-btn mt-2 ms-2">
                        <button type="button" class="btn btn-primary" id="btn_filter" data-toggle="tooltip" title="Filter Data" tabindex="-1">
                            <span class="fa fa-filter"></span>
                        </button>
                        <button type="button" class="btn btn-primary" id="btn_export" data-toggle="tooltip" title="Export Data" tabindex="-1">
                            <span class="fa fa-download"></span>
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
