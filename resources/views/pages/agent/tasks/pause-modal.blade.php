<div class="modal fade" id="pauseTaskModal-{{ $task->id }}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="pauseTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pauseTaskModalLabel">Pause Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="pauseTaskForm-{{ $task->id }}" action="{{ route('task.pause',$task) }}" method="POST">
                    @csrf
                    @method("PUT")
                    {{-- <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="remarks" class="col-form-label custom-label"><strong>REMARKS:<span class="important">*</span></strong></label>
                                    <textarea class="form-control" name="remarks" placeholder="Enter remarks here.">{{ old('remarks') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="pause('pauseTaskForm-{{ $task->id }}')"><i class="fa fa-save"></i> Pause</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
