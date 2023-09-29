<div class="modal fade" id="addPermissionModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="addPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPermissionModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="storePermissionForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="user_id" class="col-form-label custom-label"><strong>EMPLOYEE NAME:<span class="important">*</span></strong></label>
                        <select class="form-control select2" name="user_id" style="width:100%;">
                            <option value="" selected disabled>-- Select Employee -- </option>
                                @foreach ($users as $user )
                                    @if($user)
                                        <option {{ old('user_id') == $user->emp_id ? "selected" : "" }}
                                            value="{{ $user->emp_id }}">{{ ucwords($user->fullname) }} {{ ucwords($user->last_name) }}
                                        </option>
                                    @endif
                                @endforeach
                        </select>
                        <label id="user_idError" class="error" style="display:none"></label>
                    </div>

                    <div class="form-group">
                        <label for="cluster_id" class="col-form-label custom-label"><strong>CLUSTER:<span class="important">*</span></strong></label>
                        <select class="form-control select2" name="cluster_id" id="cluster_id" style="width:100%;" onchange="getClientTLOMs()">
                            <option value="" selected disabled>-- Select Cluster -- </option>
                                @foreach ($clusters as $cluster )
                                    @if($cluster)
                                        <option {{ old('cluster_id') == $cluster->id ? "selected" : "" }}
                                            value="{{ $cluster->id }}">{{ ucwords($cluster->name) }}
                                        </option>
                                    @endif
                                @endforeach
                        </select>
                        <label id="cluster_idError" class="error" style="display:none"></label>
                    </div>

                    <div class="form-group">
                        <label for="client_id" class="col-form-label custom-label"><strong>CLIENT:</strong></label>
                        <select class="form-control select2" name="client_id" id="client_id" style="width:100%;">
                            <option value="" selected disabled>-- Select Client -- </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tl_id" class="col-form-label custom-label"><strong>TEAM LEADER:</strong></label>
                        <select class="form-control select2" name="tl_id" id="tl_id" style="width:100%;">
                            <option value="" selected disabled>-- Select Team Leader -- </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="om_id" class="col-form-label custom-label"><strong>OPERATIONS MANAGER:</strong></label>
                        <select class="form-control select2" name="om_id" id="om_id" style="width:100%;">
                            <option value="" selected  disabled>-- Select Operations Manager -- </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="permission" class="col-form-label custom-label"><strong>PERMISSION:<span class="important">*</span></strong></label>
                        <select class="form-control" name="permission">
                            <option value="" disabled selected>-- Select Permission --</option>
                            @if(Auth::user()->isAdmin())<option {{ old('permission') == "admin" ? "selected" : "" }} value="admin" >Admin</option>@endif
                            <option {{ old('permission') == "accountant" ? "selected" : "" }} value="accountant" >Accountant</option>
                            <option {{ old('permission') == "team leader" ? "selected" : "" }} value="team leader">Team Leader</option>
                            <option {{ old('permission') == "operations manager" ? "selected" : "" }} value="operations manager">Operations Manager</option>
                        </select>
                        <label id="permissionError" class="error" style="display:none"></label>
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
