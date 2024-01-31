<div class="modal fade" id="uploadClientActivityModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="uploadClientActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadClientActivityModalLabel">Upload Activities</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadClientActivityForm" action="{{ route('client-activity-import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>SELECT FILE TO UPLOAD:<span class="important">*</span></strong></label>
                        <input type="file" class="form-control" name="import_file" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="store('uploadClientActivityForm')"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
