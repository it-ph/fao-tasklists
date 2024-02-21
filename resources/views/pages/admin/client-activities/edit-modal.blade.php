<div class="modal fade" id="editClientActivityModal-{{ $client_activity->id }}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editClientActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientActivityModalLabel">Edit Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editClientActivityForm-{{ $client_activity->id }}" action="{{ route('client-activities.update',$client_activity) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>ACTIVITY NAME:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="name" value ="{{ $client_activity->name }}" required @if(Auth::user()->isAccountant()) disabled @endif>
                        <label for="frequency" class="col-form-label custom-label"><strong>FREQUENCY:<span class="important">*</span></strong></label>
                        <select name="frequency" class="form-control" @if(Auth::user()->isAccountant()) disabled @endif>
                            <option value="">-- Select Frequency --</option>
                            <option value="daily" @if($client_activity->frequency == 'daily') selected @endif>Daily</option>
                            <option value="weekly" @if($client_activity->frequency == 'weekly') selected @endif>Weekly</option>
                            <option value="monthly" @if($client_activity->frequency == 'monthly') selected @endif>Monthly</option>
                        </select>
                        <label for="schedule" class="col-form-label custom-label"><strong>SCHEDULE:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="schedule" value="{{ $client_activity->schedule }}" required>
                        <label for="function" class="col-form-label custom-label"><strong>FUNCTION:<span class="important">*</span></strong></label>
                        <select name="function" class="form-control" @if(Auth::user()->isAccountant()) disabled @endif>
                            <option value="">-- Select Frequency --</option>
                            <option value="P2P" @if($client_activity->function == 'P2P') selected @endif>P2P</option>
                            <option value="O2C" @if($client_activity->function == 'O2C') selected @endif>O2C</option>
                            <option value="RTR" @if($client_activity->function == 'RTR') selected @endif>RTR</option>
                            <option value="Admin" @if($client_activity->function == 'Admin') selected @endif>Admin</option>
                        </select>
                        <input type="hidden" class="form-control" name="agent_id" value ="{{ $client_activity->agent_id }}" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="update('editClientActivityForm-{{ $client_activity->id }}')"><i class="fa fa-save"></i> Update</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
