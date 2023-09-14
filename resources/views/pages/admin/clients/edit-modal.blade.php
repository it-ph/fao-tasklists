<div class="modal fade" id="editClientModal-{{ $client->id }}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientModalLabel">Edit Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editClientForm-{{ $client->id }}" action="{{ route('clients.update',$client) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="name" class="col-form-label custom-label"><strong>CLIENT NAME:<span class="important">*</span></strong></label>
                        <input type="text" class="form-control" name="name" value ="{{ $client->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="cluster_id" class="col-form-label custom-label"><strong>CLUSTER:<span class="important">*</span></strong></label>

                        @if(Auth::user()->isAdmin())
                            <select class="form-control select2" name="cluster_id" style="width:100%;">
                                <option value="" selected disabled>-- Select Cluster -- </option>
                                    @foreach ($clusters as $cluster )
                                        @if($cluster)
                                            <option value="{{ $cluster->id }}" @if($cluster->id == $client->cluster_id) ? selected @endif>{{ ucwords($cluster->name) }}</option>
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
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="update('editClientForm-{{ $client->id }}')"><i class="fa fa-save"></i> Update</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
