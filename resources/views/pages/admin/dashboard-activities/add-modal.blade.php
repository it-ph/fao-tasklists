<div class="modal fade" id="addDashboardActivityModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="addDashboardActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDashboardActivityModalLabel">Add New Dashboard Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="storeDashboardActivityForm" action="{{ route('dashboard-activities.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>DASHBOARD ACTIVITY NAME:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="store('storeDashboardActivityForm')"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
