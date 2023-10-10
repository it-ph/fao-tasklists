<div class="modal fade" id="editPermissionModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPermissionModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPermissionForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="user_id" class="col-form-label custom-label"><strong>EMPLOYEE NAME:<span class="important">*</span></strong></label>
                        <select class="form-control select2" name="user_id" id="user_id_edit" style="width:100%;">
                            <option value="" disabled>-- Select Employee -- </option>
                                @foreach ($users as $user )
                                    @if($user)
                                        @isset($user)
                                            <option {{ old('user_id') == $user->emp_id ? "selected" : "" }}
                                                value="{{ $user->emp_id }}">@isset($user){{ ucwords($user->fullname) }} {{ ucwords($user->last_name) }}@endisset
                                            </option>
                                        @endisset
                                    @endif
                                @endforeach
                        </select>
                        <label id="user_id_editError" class="error"></label>
                    </div>

                    <div class="form-group">
                        <label for="cluster_id" class="col-form-label custom-label"><strong>CLUSTER:<span class="important">*</span></strong></label>
                        <select class="form-control select2" name="cluster_id" id="cluster_id_edit" style="width:100%;">
                            <option value="" disabled>-- Select Cluster -- </option>
                                @foreach ($clusters as $cluster )
                                    @if($cluster)
                                        <option {{ old('cluster_id') == $cluster->id ? "selected" : "" }}
                                            value="{{ $cluster->id }}">{{ ucwords($cluster->name) }}
                                        </option>
                                    @endif
                                @endforeach
                        </select>
                        <label id="cluster_id_editError" class="error"></label>
                    </div>

                    <div class="form-group">
                        <label for="client_id" class="col-form-label custom-label"><strong>CLIENT:</strong></label>
                        <select class="form-control select2" name="client_id" id="client_id_edit" style="width:100%;">
                            <option value="">-- Select Client -- </option>
                                @foreach ($clients as $client )
                                    @if($client)
                                        <option {{ old('client_id') == $client->id ? "selected" : "" }}
                                            value="{{ $client->id }}">{{ ucwords($client->name) }}
                                        </option>
                                    @endif
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tl_id" class="col-form-label custom-label"><strong>TEAM LEADER:</strong></label>
                        <select class="form-control select2" name="tl_id" id="tl_id_edit" style="width:100%;">
                            <option value="">-- Select Team Leader -- </option>
                                @foreach ($tls as $tl )
                                    @if($tl)
                                        @isset($tl->theuser)
                                            <option {{ old('tl_id') == $tl->theuser->emp_id ? "selected" : "" }}
                                                value="{{ $tl->theuser->emp_id }}">@isset($tl->theuser){{ ucwords($tl->theuser->fullname) }} {{ ucwords($tl->theuser->last_name) }}@endisset
                                            </option>
                                        @endisset
                                    @endif
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="om_id" class="col-form-label custom-label"><strong>OPERATIONS MANAGER:</strong></label>
                        <select class="form-control select2" name="om_id" id="om_id_edit" style="width:100%;">
                            <option value="">-- Select Operations Manager -- </option>
                                @foreach ($oms as $om )
                                    @if($om)
                                        @isset($om->theuser)
                                            <option {{ old('om_id') == $om->theuser->emp_id ? "selected" : "" }}
                                                value="{{ $om->theuser->emp_id }}">@isset($om->theuser){{ ucwords($om->theuser->fullname) }} {{ ucwords($om->theuser->last_name) }}@endisset
                                            </option>
                                        @endisset
                                    @endif
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="permission" class="col-form-label custom-label"><strong>PERMISSION:<span class="important">*</span></strong></label>
                        <select class="form-control" name="permission" id="permission_edit">
                            <option value="" disabled selected>-- Select Permission --</option>
                            <option {{ old("permission") == "admin" ? "selected" : "" }} value="admin"@if(!Auth::user()->isAdmin()) disabled @endif>Admin</option>
                            <option {{ old("permission") == "accountant" ? "selected" : "" }} value="accountant">Accountant</option>
                            <option {{ old("permission") == "team leader" ? "selected" : "" }} value="team leader">Team Leader</option>
                            <option {{ old("permission") == "operations manager" ? "selected" : "" }} value="operations manager">Operations Manager</option>
                        </select>
                        <label id="permission_editError" class="error"></label>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_update" class="btn btn-primary waves-effect waves-light"><i class="fa fa-save"></i> Update</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
