<div class="modal fade" id="stopTaskModal-{{ $task->id }}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="stopTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stopTaskModalLabel">Stop Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="stopTaskForm-{{ $task->id }}" action="{{ route('task.stop',$task) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="status" class="col-form-label custom-label"><strong>STATUS:<span class="important">*</span></strong></label>
                                    <select name="status" class="form-control" required>
                                        <option value="" selected disabled>In Progress (current)</option>
                                        <option value="On Hold">On Hold</option>
                                        <option value="Completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="volume" class="col-form-label custom-label"><strong>VOLUME:<span class="important">*</span></strong></label>
                                    <input type="text" class="form-control" name="volume" value="{{ old('volume') }}" placeholder="Enter Volume">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="remarks" class="col-form-label custom-label"><strong>REMARKS:<span class="important">*</span></strong></label>
                                    <textarea class="form-control" name="remarks" placeholder="Enter remarks here.">{{ old('remarks') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="stop('stopTaskForm-{{ $task->id }}')"><i class="fa fa-save"></i> Stop</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
