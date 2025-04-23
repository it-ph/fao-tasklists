<div class="modal fade" id="editClientModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientModalLabel">Edit Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editClientForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>CLIENT NAME:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="name" id="name_edit" required>
                        <label id="name_editError" class="error"></label>
                    </div>
                    <div class="form-group">
                        <label for="cluster_id" class="col-form-label custom-label"><strong>CLUSTER:<span class="important">*</span></strong></label>

                        @if(auth()->user()->isAdmin())
                            <select class="form-control select2" name="cluster_id" id="cluster_id_edit" style="width:100%;">
                                <option value="" selected disabled>-- Select Cluster -- </option>
                                    @foreach ($clusters as $cluster )
                                        @if($cluster)
                                            <option value="{{ $cluster->id }}">{{ ucwords($cluster->name) }}</option>
                                        @endif
                                    @endforeach
                            </select>
                            <label id="cluster_id_editError" class="error"></label>
                        @elseif(auth()->user()->thepermisssion->cluster_id)
                            <input class="form-control" type="hidden" name="cluster_id" value="{{ auth()->user()->thepermisssion->cluster_id }}">
                            <input class="form-control" type="text" disabled value="{{ auth()->user()->thepermisssion->thecluster->name }}">
                        @endif

                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_update" class="btn btn-primary waves-effect waves-light"><i class="fa fa-save"></i>Update</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
