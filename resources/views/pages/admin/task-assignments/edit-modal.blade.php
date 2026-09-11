<div class="modal fade" id="editTaskModal" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="agent_id" class="col-form-label custom-label"><strong>EMPLOYEE NAME:<span class="important">*</span></strong></label>
                                    {{-- <input class="form-control" type="hidden" name="agent_id" id="agent_id">
                                    <input class="form-control" type="text" disabled id="employee_name"> --}}

                                    <select class="form-control select2" name="agent_id" id='agent_id' style="width:100%;">
                                        <option value="" selected disabled>-- Select an Employee -- </option>
                                            @foreach ($permissions as $permission )
                                                @if($permission)
                                                    <option value="{{ $permission->id }}">{{ ucwords($permission->fullname) }}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="schedule" class="col-form-label custom-label"><strong>SCHEDULE:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="date" name="schedule" id="schedule_edit">
                                    <label id="schedule_editError" class="error" for="name"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="applicable_month" class="col-form-label custom-label"><strong>APPLICABLE MONTH:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="month" name="applicable_month" id="applicable_month_edit">
                                    <label id="applicable_month_editError" class="error" for="name"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="cluster_id" class="col-form-label custom-label"><strong>CLUSTER:<span class="important">*</span></strong></label>
                                    <select class="form-control select2" name="cluster_id_edit" id='cluster_id_edit' style="width:100%;" disabled>
                                        <option value="" selected disabled>-- Select Cluster -- </option>
                                            @foreach ($clusters as $cluster )
                                                @if($cluster)
                                                    <option value="{{ $cluster->id }}">{{ ucwords($cluster->name) }}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                    <label id="cluster_id_editError" class="error" for="name"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="client_id" class="col-form-label custom-label"><strong>CLIENT NAME:<span class="important">*</span></strong></label>
                                    <select class="form-control select2" name="client_id_edit" id="client_id_edit" style="width:100%;" disabled>
                                        <option value="" selected disabled>-- Select Client -- </option>
                                            @foreach ($clients as $client )
                                                @if($client)
                                                    <option value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                    <label id="client_id_editError" class="error" for="name"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="activity_name" class="col-form-label custom-label"><strong>Activity Name:<span class="important">*</span></strong></label>
                                    <textarea class="form-control" name="activity_name" id="activity_name_edit" placeholder="Type the activity name here"></textarea>
                                    <label id="activity_name_editError" class="error" for="name"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="client_function" class="col-form-label custom-label"><strong>CLIENT FUNCTION:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="text" name="client_function" id="client_function_edit">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="eclerx_function" class="col-form-label custom-label"><strong>ECLERX FUNCTION:<span class="important">*</span></strong></label>
                                    <select name="eclerx_function" class="form-control" id="eclerx_function_edit">
                                    <option value="">-- Select Eclerx Function --</option>
                                    <option value="Procure to Pay (P2P)">Procure to Pay (P2P)</option>
                                    <option value="Order to Cash (O2C)">Order to Cash (O2C)</option>
                                    <option value="Record to Report (R2R)">Record to Report (R2R)</option>
                                    <option value="Client Admin">Client Admin</option>
                                    <option value="Personiv Admin">Personiv Admin</option>
                                </select>
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
                                    <input type="text" class="form-control" name="status" id="status_edit" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="start_date" class="col-form-label custom-label"><strong>START TIME:</span></strong></label>
                                    <input type="datetime-local" class="form-control" name="start_date" id="start_date_edit">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="end_date" class="col-form-label custom-label"><strong>END TIME:</span></strong></label>
                                    <input type="datetime-local" class="form-control" name="end_date" id="end_date_edit">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="actual_handling_time" class="col-form-label custom-label"><strong>ACTUAL HANDLING TIME:</span></strong></label>
                                    <input type="text" class="form-control" name="actual_handling_time" id="actual_handling_time_edit" placeholder="00:00:00:00">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="timeliness" class="col-form-label custom-label"><strong>TIMELINESS:<span class="important">*</span></strong></label>
                                    <select class="form-control select2" name="timeliness" id="timeliness_edit" style="width:100%;" disabled>
                                        <option value="" selected disabled>-- Select Timeliness -- </option>
                                        <option value="Green">Green</option>
                                        <option value="Red">Red</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="Quality" class="col-form-label custom-label"><strong>Quality:<span class="important">*</span></strong></label>
                                    <select class="form-control select2" name="quality" id="quality_edit" style="width:100%;">
                                        <option value="" selected disabled>-- Select Quality -- </option>
                                        <option value="Green">Green</option>
                                        <option value="Red">Red</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="remarks" class="col-form-label custom-label"><strong>REMARKS:</span></strong></label>
                                    <textarea class="form-control" name="remarks" id="remarks_edit"></textarea>
                                </div>
                            </div>
                        </div>
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
