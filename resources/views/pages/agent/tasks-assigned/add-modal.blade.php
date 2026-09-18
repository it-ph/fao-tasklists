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
                                    <input class="form-control" type="hidden" name="agent_id" value="{{ auth()->user()->id }}">
                                    <input class="form-control" type="text" disabled value="{{ auth()->user()->fullname }} {{ auth()->user()->last_name }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="schedule" class="col-form-label custom-label"><strong>SCHEDULE:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="date" name="schedule" @if(auth()->user()->schedule) value="{{ date('Y-m-d', strtotime(auth()->user()->schedule)) }}" @endif>
                                    <label id="scheduleError" class="error" style="display:none"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="applicable_month" class="col-form-label custom-label"><strong>APPLICABLE MONTH:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="month" name="applicable_month" id="applicable_month" value="{{ old('applicable_month') }}">
                                    <label id="applicable_monthError" class="error" style="display:none"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="cluster_id" class="col-form-label custom-label"><strong>CLUSTER:<span class="important">*</span></strong></label>
                                    @if(auth()->user()->cluster_id)
                                        <input class="form-control" type="hidden" name="cluster_id" value="{{ auth()->user()->cluster_id }}">
                                        <input class="form-control" type="text" disabled value="{{ auth()->user()->thecluster->name }}">
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
                                    @if(auth()->user()->client_id)
                                        <input class="form-control" type="hidden" name="client_id" value="{{ auth()->user()->client_id }}">
                                        <input class="form-control" type="text" disabled value="{{ auth()->user()->theclient->name }}">
                                    @else
                                        <select class="form-control select2" name="client_id" id="client_id" style="width:100%;">
                                            <option value="" selected disabled>-- Select Client -- </option>
                                                @foreach ($clients as $client )
                                                    @if($client)
                                                        <option value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
                                                    @endif
                                                @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="activity_name" class="col-form-label custom-label"><strong>ACTIVITY NAME:<span class="important">*</span></strong></label>
                                    <textarea class="form-control" name="activity_name" id="activity_name" placeholder="Type the activity name here">{{ old('activity_name') }}</textarea>
                                    <label id="activity_nameError" class="error" style="display:none"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="client_function" class="col-form-label custom-label"><strong>CLIENT FUNCTION:</strong></label>
                                    <input class="form-control" type="text" name="client_function" id="client_function">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="eclerx_function" class="col-form-label custom-label"><strong>ECLERX FUNCTION:<span class="important">*</span></strong></label>
                                    <select name="eclerx_function" class="form-control" id="eclerx_function">
                                    <option value="" selected disabled>-- Select Eclerx Function --</option>
                                    <option value="Procure to Pay (P2P)">Procure to Pay (P2P)</option>
                                    <option value="Order to Cash (O2C)">Order to Cash (O2C)</option>
                                    <option value="Record to Report (R2R)">Record to Report (R2R)</option>
                                    <option value="Client Admin">Client Admin</option>
                                    <option value="Personiv Admin">Personiv Admin</option>
                                </select>
                                <label id="eclerx_functionError" class="error" style="display:none"></label>
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
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="timeliness" class="col-form-label custom-label"><strong>TIMELINESS:<span class="important">*</span></strong></label>
                                    <select class="form-control select2" name="timeliness" id="timeliness" style="width:100%;" disabled>
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
                                    <select class="form-control select2" name="quality" id="quality" style="width:100%;" disabled>
                                        {{-- <option value="" selected disabled>-- Select Quality -- </option> --}}
                                        <option value="Green" selected>Green</option>
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
