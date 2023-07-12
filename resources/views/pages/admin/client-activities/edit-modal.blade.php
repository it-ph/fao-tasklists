<div class="modal fade" id="editClientActivityModal-{{ $client_activity->id }}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editClientActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientActivityModalLabel">Edit Client Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editClientActivityForm-{{ $client_activity->id }}" action="{{ route('client-activities.update',$client_activity) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>CLIENT ACTIVITY NAME:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="name" value ="{{ $client_activity->name }}" required>
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
