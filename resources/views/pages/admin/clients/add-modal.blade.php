<div class="modal fade" id="addClientModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="storeClientForm" action="{{ route('clients.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>CLIENT NAME:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="cluster_id" class="col-form-label custom-label"><strong>CLUSTER:<span class="important">*</span></strong></label>
                        @if(Auth::user()->isAdmin())
                            <select class="form-control select2" name="cluster_id" style="width:100%;">
                                <option value="" selected disabled>-- Select Cluster -- </option>
                                    @foreach ($clusters as $cluster )
                                        @if($cluster)
                                            <option {{ old('cluster_id') == $cluster->id ? "selected" : "" }}
                                                value="{{ $cluster->id }}">{{ ucwords($cluster->name) }}
                                            </option>
                                        @endif
                                    @endforeach
                            </select>
                        @elseif(Auth::user()->thepermisssion->cluster_id)
                            <input class="form-control" type="hidden" name="cluster_id" value="{{ Auth::user()->thepermisssion->cluster_id }}">
                            <input class="form-control" type="text" disabled value="{{ Auth::user()->thepermisssion->thecluster->name }}">
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="store('storeClientForm')"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
