<div class="modal fade" id="editShiftDate" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editShiftDateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editShiftDateModalLabel">SHIFT DATE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editShiftDateForm" action="{{ route('shift-date.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>DEFAULT SHIFT DATE: <span class="important">*</span></strong></label>
                        <input class="form-control" type="date" name="shift_date" @if(Auth::user()->thepermisssion->shift_date) value="{{ date('Y-m-d', strtotime(Auth::user()->thepermisssion->shift_date)) }}" @endif>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="update('editShiftDateForm')"><i class="fa fa-save"></i> Update</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
