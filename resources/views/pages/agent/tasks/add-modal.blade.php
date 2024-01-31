<div class="modal fade" id="addTaskModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="addTaskModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModal">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="storeTaskForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="agent_id" class="col-form-label custom-label"><strong>EMPLOYEE NAME:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="hidden" name="agent_id" value="{{ Auth::user()->emp_id }}">
                                    <input class="form-control" type="text" disabled value="{{ Auth::user()->fullname }} {{ Auth::user()->last_name }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="shift_date" class="col-form-label custom-label"><strong>SHIFT DATE:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="date" name="shift_date" @if(Auth::user()->thepermisssion->shift_date) value="{{ date('Y-m-d', strtotime(Auth::user()->thepermisssion->shift_date)) }}" @endif>
                                    <label id="shift_dateError" class="error" style="display:none"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="date_received" class="col-form-label custom-label"><strong>DATE RECEIVED:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="date" name="date_received" id="date_received" value="{{ old('date_received') }}">
                                    <label id="date_receivedError" class="error" style="display:none"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="cluster_id" class="col-form-label custom-label"><strong>CLUSTER:<span class="important">*</span></strong></label>
                                    @if(Auth::user()->thepermisssion->cluster_id)
                                        <input class="form-control" type="hidden" name="cluster_id" value="{{ Auth::user()->thepermisssion->cluster_id }}">
                                        <input class="form-control" type="text" disabled value="{{ Auth::user()->thepermisssion->thecluster->name }}">
                                    @else
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
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="client_id" class="col-form-label custom-label"><strong>CLIENT NAME:<span class="important">*</span></strong></label>
                                    @if(Auth::user()->thepermisssion->client_id)
                                        <input class="form-control" type="hidden" name="client_id" value="{{ Auth::user()->thepermisssion->client_id }}">
                                        <input class="form-control" type="text" disabled value="{{ Auth::user()->thepermisssion->theclient->name }}">
                                    @else
                                        <select class="form-control select2" name="client_id" id="client_id" style="width:100%;">
                                            <option value="" selected disabled>-- Select Client -- </option>
                                                @foreach ($clients as $client )
                                                    @if($client)
                                                        <option value="{{ $client->id }}">{{ ucwords($client->name) }} </option>
                                                    @endif
                                                @endforeach
                                        </select>
                                        <label id="client_idError" class="error" style="display:none"></label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="client_activity_id" class="col-form-label custom-label"><strong>ACTIVITY:<span class="important">*</span></strong></label>
                                    <select class="form-control select2" name="client_activity_id" id="client_activity_id" style="width:100%;">
                                        <option value="" selected disabled>-- Select Activity -- </option>
                                            @foreach ($user_client_activities as $user_client_activity)
                                                @if($user_client_activity)
                                                    <option value="{{ $user_client_activity->id }}">{{ ucwords($user_client_activity->name) }} </option>
                                                @endif
                                            @endforeach
                                    </select>
                                    <label id="client_activity_idError" class="error" style="display:none"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="description" class="col-form-label custom-label"><strong>DESCRIPTION:<span class="important">*</span></strong></label>
                                    <textarea class="form-control" name="description" id="description" placeholder="Type the description here">{{ old('description') }}</textarea>
                                    <label id="descriptionError" class="error" style="display:none"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="status" class="col-form-label custom-label"><strong>STATUS:</strong></label>
                                    <select class="form-control" name="status" disabled>
                                        <option value="" disabled>-- Select Status --</option>
                                        <option value="In Progress" selected>In Progress</option>
                                        <option value="Completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="start_date" class="col-form-label custom-label"><strong>START TIME:</span></strong></label>
                                    <input type="text" class="form-control" name="start_date" placeholder="Start Time" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="end_date" class="col-form-label custom-label"><strong>END TIME:</span></strong></label>
                                    <input type="text" class="form-control" name="end_date" placeholder="End Time" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="actual_handling_time" class="col-form-label custom-label"><strong>ACTUAL HANDLING TIME:</span></strong></label>
                                    <input type="text" class="form-control" name="actual_handling_time" placeholder="00:00:00:00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="volume" class="col-form-label custom-label"><strong>VOLUME:</span></strong></label>
                                    <input type="text" class="form-control" name="volume" placeholder="Volume" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="remarks" class="col-form-label custom-label"><strong>REMARKS:</span></strong></label>
                                    <textarea class="form-control" name="remarks" placeholder="Remarks" readonly></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btn_save"  class="btn btn-primary waves-effect waves-light"><i class="fa fa-save"></i> Save and Start</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
