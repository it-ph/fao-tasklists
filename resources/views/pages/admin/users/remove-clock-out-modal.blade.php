<div class="modal fade" id="removeCOModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="removeCOModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeCOModalLabel">Remove Clock-Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="removeCOForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mt-2 mb-2">
                                <div class="form-group text-center">
                                    <h4>Are you sure you want to allow this employee to continue working for this shift?</h4>
                                    <h5>This will remove the current clock-out timestamp.</h5>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_allow" class="btn btn-primary waves-effect waves-light"><i class="fa fa-check"></i> Yes, Allow!</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
