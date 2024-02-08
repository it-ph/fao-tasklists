<div class="modal fade" id="resumeTaskModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="resumeTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resumeTaskModalLabel">Resume Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="resumeTaskForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mt-2 mb-2">
                                <div class="form-group text-center">
                                    <h3>Are you sure?</h3>
                                    <h5>You won't be able to revert this!</h5>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_resume" class="btn btn-primary waves-effect waves-light"><i class="fa fa-play"></i> Resume</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
