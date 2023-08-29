<div class="modal fade" id="addPermissionModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="addPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPermissionModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="storeClientActivityForm" action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="user_id" class="col-form-label custom-label"><strong>EMPLOYEE NAME:<span class="important">*</span></strong></label>
                        <select class="form-control select2" name="user_id" style="width:100%;">
                            <option value="" selected disabled>-- Select Employee -- </option>
                                @foreach ($users as $user )
                                    @if($user)
                                        @isset($user->employeeprofile)
                                            <option {{ old('user_id') == $user->id ? "selected" : "" }}
                                                value="{{ $user->id }}">@isset($user->employeeprofile){{ ucwords($user->employeeprofile->fullname) }} {{ ucwords($user->employeeprofile->last_name) }}@endisset
                                            </option>
                                        @endisset
                                    @endif
                                @endforeach
                        </select>
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
                    </div>

                    <div class="form-group">
                        <label for="client_id" class="col-form-label custom-label"><strong>CLIENT:</strong></label>
                        <select class="form-control select2" name="client_id" id="client_id" style="width:100%;">
                            <option value="" selected disabled>-- Select Client -- </option>
                                {{-- @foreach ($clients as $client )
                                    @if($client)
                                        <option {{ old('client_id') == $client->id ? "selected" : "" }}
                                            value="{{ $client->id }}">{{ ucwords($client->name) }}
                                        </option>
                                    @endif
                                @endforeach --}}
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tl_id" class="col-form-label custom-label"><strong>TEAM LEADER:</strong></label>
                        <select class="form-control select2" name="tl_id" id="tl_id" style="width:100%;">
                            <option value="" selected disabled>-- Select Team Leader -- </option>
                                {{-- @foreach ($tls as $tl )
                                    @if($tl)
                                        <option value="{{ $tl->theuser->id }}">
                                            @isset($tl->theuser->employeeprofile){{ ucwords($tl->theuser->employeeprofile->fullname) }} {{ ucwords($tl->theuser->employeeprofile->last_name) }}@endisset
                                        </option>
                                    @endif
                                @endforeach --}}
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="om_id" class="col-form-label custom-label"><strong>OPERATIONS MANAGER:</strong></label>
                        <select class="form-control select2" name="om_id" id="om_id" style="width:100%;">
                            <option value="" selected  disabled>-- Select Operations Manager -- </option>
                                {{-- @foreach ($oms as $om )
                                    @if($om)
                                        <option value="{{ $om->theuser->id }}">
                                            @isset($om->theuser->employeeprofile){{ ucwords($om->theuser->employeeprofile->fullname )}} {{ ucwords($om->theuser->employeeprofile->last_name) }}@endisset
                                        </option>
                                    @endif
                                @endforeach --}}
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="permission" class="col-form-label custom-label"><strong>PERMISSION:<span class="important">*</span></strong></label>
                        <select class="form-control" name="permission">
                            <option value="" disabled selected>-- Select Permission --</option>
                            {{-- <option {{ old('permission') /== "admin" ? "selected" : "" }} value="admin" >Admin</option> --}}
                            <option {{ old('permission') == "accountant" ? "selected" : "" }} value="accountant" >Accountant</option>
                            <option {{ old('permission') == "team leader" ? "selected" : "" }} value="team leader">Team Leader</option>
                            <option {{ old('permission') == "operations manager" ? "selected" : "" }} value="operations manager">Operations Manager</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="store('storeClientActivityForm')"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
