<div class="modal fade" id="addClientModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="storeClientForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>CLIENT NAME:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="name" id="name">
                        <label id="nameError" class="error"></label>
                    </div>
                    <div class="form-group">
                        <label for="cluster_id" class="col-form-label custom-label"><strong>CLUSTER:<span class="important">*</span></strong></label>
                        @if(auth()->user()->isAdmin())
                            <select class="form-control select2" name="cluster_id" id="cluster_id" style="width:100%;">
                                <option value="" selected disabled>-- Select Cluster -- </option>
                                    @foreach ($clusters as $cluster )
                                        @if($cluster)
                                            <option {{ old('cluster_id') == $cluster->id ? "selected" : "" }}
                                                value="{{ $cluster->id }}">{{ ucwords($cluster->name) }}
                                            </option>
                                        @endif
                                    @endforeach
                            </select>
                            <label id="cluster_idError" class="error"></label>
                        @elseif(auth()->user()->cluster_id)
                            <input class="form-control" type="hidden" name="cluster_id" value="{{ auth()->user()->cluster_id }}">
                            <input class="form-control" type="text" disabled value="{{ auth()->user()->thecluster->name }}">
                        @endif
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
