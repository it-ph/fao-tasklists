<div class="modal fade" id="addChangeRequestModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="addChangeRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addChangeRequestModalLabel">Create New Change Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="storeChangeRequestForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>TASK ID:<span class="important">*</span></strong></label>
                        <select class="form-control select2" name="task_id" id="task_id" style="width:100%;">
                            <option value="" selected disabled>-- Select Task ID -- </option>
                            @foreach ($tasks as $task)
                                @if($task)
                                    <option {{ old('task_id')==$task->id ? "selected" : "" }}
                                        value="{{ $task->id }}">{{ ucwords($task->id) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <label id="task_idError" class="error"></label>
                    </div>
                    <div class="form-group">
                        <label for="remarks" class="col-form-label custom-label"><strong>REMARKS:<span class="important">*</span></strong></label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="5"></textarea>
                        <label id="remarksError" class="error"></label>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_save" class="btn btn-primary waves-effect waves-light"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
