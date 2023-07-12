<div class="modal fade" id="editPermissionModal-{{ $permission->id }}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPermissionModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPermissionForm-{{ $permission->id }}" action="{{ route('permissions.update',$permission) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="user_id" class="col-form-label custom-label"><strong>EMPLOYEE NAME:<span class="important">*</span></strong></label>
                        <select class="form-control select2" name="user_id" style="width:100%;">
                            <option value="">-- Select Employee -- </option>
                                @foreach ($users as $user )
                                    @if($user)
                                        @isset($user->employeeprofile)
                                            <option {{ old('user_id') == $user->id ? "selected" : "" }}
                                                value="{{ $user->id }}" @if($permission->user_id == $user->id) selected @endif>@isset($user->employeeprofile){{ ucwords($user->employeeprofile->fullname) }} {{ ucwords($user->employeeprofile->last_name) }}@endisset
                                            </option>
                                        @endisset
                                    @endif
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cluster_id" class="col-form-label custom-label"><strong>CLUSTER:<span class="important">*</span></strong></label>
                        <select class="form-control select2" name="cluster_id" style="width:100%;">
                            <option value="">-- Select Cluster -- </option>
                                @foreach ($clusters as $cluster )
                                    @if($cluster)
                                        <option {{ old('cluster_id') == $cluster->id ? "selected" : "" }}
                                            value="{{ $cluster->id }}" @if($permission->cluster_id == $cluster->id) selected @endif>{{ ucwords($cluster->name) }}
                                        </option>
                                    @endif
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="client_id" class="col-form-label custom-label"><strong>CLIENT:<span class="important">*</span></strong></label>
                        <select class="form-control select2" name="client_id" style="width:100%;">
                            <option value="">-- Select Client -- </option>
                                @foreach ($clients as $client )
                                    @if($client)
                                        <option {{ old('client_id') == $client->id ? "selected" : "" }}
                                            value="{{ $client->id }}" @if($permission->client_id == $client->id) selected @endif>{{ ucwords($client->name) }}
                                        </option>
                                    @endif
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tl_id" class="col-form-label custom-label"><strong>TEAM LEAD:</strong></label>
                        <select class="form-control select2" name="tl_id" style="width:100%;">
                            <option value="">-- Select Team Lead -- </option>
                                @foreach ($tls as $tl )
                                    @if($tl)
                                        @isset($tl->theuser->employeeprofile)
                                            <option {{ old('tl_id') == $tl->theuser->id ? "selected" : "" }}
                                                value="{{ $tl->theuser->id }}" @if($permission->tl_id == $tl->theuser->id) selected @endif>@isset($tl->theuser->employeeprofile){{ ucwords($tl->theuser->employeeprofile->fullname) }} {{ ucwords($tl->theuser->employeeprofile->last_name) }}@endisset
                                            </option>
                                        @endisset
                                    @endif
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="om_id" class="col-form-label custom-label"><strong>OPERATIONS MANAGER:</strong></label>
                        <select class="form-control select2" name="om_id" style="width:100%;">
                            <option value="">-- Select Operations Manager -- </option>
                                @foreach ($oms as $om )
                                    @if($om)
                                        @isset($om->theuser->employeeprofile)
                                            <option {{ old('om_id') == $om->theuser->id ? "selected" : "" }}
                                                value="{{ $om->theuser->id }}" @if($permission->om_id == $om->theuser->id) selected @endif>@isset($om->theuser->employeeprofile){{ ucwords($om->theuser->employeeprofile->fullname) }} {{ ucwords($om->theuser->employeeprofile->last_name) }}@endisset
                                            </option>
                                        @endisset
                                    @endif
                                @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="permission" class="col-form-label custom-label"><strong>PERMISSION:<span class="important">*</span></strong></label>
                        <select class="form-control" name="permission">
                            <option value="" disabled selected>-- Select Permission --</option>
                            <option {{ old("permission") == "accountant" ? "selected" : "" }} value="accountant" @if($permission->permission == "accountant") selected @endif>Accountant</option>
                            <option {{ old("permission") == "team lead" ? "selected" : "" }} value="team lead" @if($permission->permission == "team lead") selected @endif>Team Lead</option>
                            <option {{ old("permission") == "operations manager" ? "selected" : "" }} value="operations manager" @if($permission->permission == "operations manager") selected @endif>Operations Manager</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="update('editPermissionForm-{{ $permission->id }}')"><i class="fa fa-save"></i> Update</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
