<div class="col-lg-4 d-flex align-items-stretch">
    <div class="card w-100">
        <div class="card-body">
            <!-- Row layout ensures labels sit perfectly above inputs -->
            <div class="row g-2 align-items-end">
                
                <!-- User Filter -->
                <div class="col">
                    <label for="user_filter" class="form-label small fw-bold text-muted mb-1">USER</label>
                    <select class="form-select" name="user_filter" id="user_filter">
                        <option value="All Users">All Users</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="col" id="div_filter">
                    <label for="filter_option" class="form-label small fw-bold text-muted mb-1">DATE</label>
                    <input type="date" class="form-control" id="filter_option" value="{{ date('Y-m-d') }}" required />
                </div>

                <!-- Detection Filter -->
                <div class="col">
                    <label for="detection_filter" class="form-label small fw-bold text-muted mb-1">DETECTION</label>
                    <select class="form-select" name="detection_filter" id="detection_filter">
                        <option value="All Methods">All Methods</option>
                        <option value="Screen Lock">Screen Lock</option>
                        <option value="App Switch">App Switch</option>
                        <option value="No Interaction">No Interaction</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="col-auto">
                    <button type="button" class="btn btn-primary me-2" id="btn_filter" data-bs-toggle="tooltip" title="Filter Data" tabindex="-1">
                        <span class="fa fa-filter"></span>
                    </button>
                    <button type="button" class="btn btn-primary" id="btn_export" data-bs-toggle="tooltip" title="Export Data" tabindex="-1">
                        <span class="fa fa-download"></span>
                    </button>
                </div>


            </div>
        </div>
    </div>
</div>