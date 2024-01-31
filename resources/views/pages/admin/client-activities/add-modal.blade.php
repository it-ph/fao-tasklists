<div class="modal fade" id="addClientActivityModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="addClientActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientActivityModalLabel">Add New Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="storeClientActivityForm" action="{{ route('client-activities.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>ACTIVITY NAME:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="name" required>
                        <label for="frequency" class="col-form-label custom-label"><strong>FREQUENCY:<span class="important">*</span></strong></label>
                        <select name="frequency" class="form-control">
                            <option value="">-- Select Frequency --</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                        <label for="schedule" class="col-form-label custom-label"><strong>SCHEDULE:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="schedule" required>
                        <label for="function" class="col-form-label custom-label"><strong>FUNCTION:<span class="important">*</span></strong></label>
                        <select name="function" class="form-control">
                            <option value="">-- Select Function --</option>
                            <option value="P2P">P2P</option>
                            <option value="O2C">O2C</option>
                            <option value="RTR">RTR</option>
                            <option value="Admin">Admin</option>
                        </select>
                        <input type="hidden" class="form-control" name="agent_id" value="{{ \Request::get('user_id') }}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="store('storeClientActivityForm')"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
