<div class="modal fade" id="editChangeRequestModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editChangeRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editChangeRequestModalLabel">Edit Change Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editChangeRequestForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" id="cr_update_close">
                        <label for="task_id" class="col-form-label custom-label"><strong>TASK ID:<span class="important">*</span></strong></label>
                        <select class="form-control select2" name="task_id" id="task_id_edit" style="width:100%;">
                            <option value="" selected disabled>-- Select Target Reference --</option>                    
                            <optgroup label="My Tasks">
                                @foreach ($tasks as $task)
                                    <!-- Value attribute holds the pure number string key -->
                                    <option value="{{ $task->id }}">{{ $task->id }}</option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Assigned Tasks">
                                @foreach ($task_assignments as $assignment)
                                    <!-- Value attribute holds the TA + number key tracking string -->
                                    <option value="TA{{ $assignment->id }}">TA{{ $assignment->id }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <label id="task_id_editError" class="error"></label>
                    </div>
                    <div class="form-group">
                        <label for="remarks" class="col-form-label custom-label"><strong>REMARKS:<span class="important">*</span></strong></label>
                        <textarea class="form-control" name="remarks" id="remarks_edit" rows="5"></textarea>
                        <label id="remarks_editError" class="error"></label>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_update" class="btn btn-primary waves-effect waves-light"><i class="fa fa-save"></i> Update</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" id="btn-times" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
