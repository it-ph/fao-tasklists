<div class="modal fade" id="stopTaskModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="stopTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stopTaskModalLabel">Stop Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="stopTaskForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="status" class="col-form-label custom-label"><strong>STATUS:<span class="important">*(change status)</span></strong></label>
                                    <select name="status" id="status_stop" class="form-control">
                                        <option value="" selected disabled>In Progress (current)</option>
                                        {{-- <option value="On Hold">On Hold</option> --}}
                                        <option value="Completed">Completed</option>
                                    </select>
                                    <label id="status_stopError" class="error"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="volume" class="col-form-label custom-label"><strong>VOLUME:<span class="important">*</span></strong></label>
                                    <input type="number" class="form-control" name="volume" id="volume_stop" placeholder="Enter Volume">
                                    <label id="volume_stopError" class="error"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="remarks" class="col-form-label custom-label"><strong>REMARKS:<span class="important">(optional)</span></strong></label>
                                    <textarea class="form-control" name="remarks" id="remarks_stop" placeholder="Enter remarks here."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_stop" class="btn btn-primary waves-effect waves-light"><i class="fa fa-stop"></i> Stop</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
