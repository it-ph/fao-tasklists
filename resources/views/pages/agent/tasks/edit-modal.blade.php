<div class="modal fade" id="editTaskModal-{{ $task->id }}" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm-{{ $task->id }}" action="{{ route('task.update',$task) }}" method="POST">
                    @csrf
                    @method("PUT")

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="agent_id" class="col-form-label custom-label"><strong>EMPLOYEE NAME:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="hidden" name="agent_id" value="{{ Auth::id() }}">
                                    <input class="form-control" type="text" disabled value="@isset(Auth::user()->employeeprofile) {{ Auth::user()->employeeprofile->fullname }} {{ Auth::user()->employeeprofile->last_name }} @endisset">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="shift_date" class="col-form-label custom-label"><strong>SHIFT DATE:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="date" name="shift_date" value="{{ date('Y-m-d', strtotime($task->shift_date)) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="cluster_id" class="col-form-label custom-label"><strong>CLUSTER:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="hidden" name="cluster_id" value="{{ Auth::user()->thepermisssion->cluster_id }}">
                                    <input class="form-control" type="text" disabled value="{{ Auth::user()->thepermisssion->thecluster->name }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="client_id" class="col-form-label custom-label"><strong>CLIENT NAME:<span class="important">*</span></strong></label>
                                    <input class="form-control" type="hidden" name="client_id" value="{{ Auth::user()->thepermisssion->client_id }}">
                                    <input class="form-control" type="text" disabled value="{{ Auth::user()->thepermisssion->theclient->name }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="dashboard_activity_id" class="col-form-label custom-label"><strong>DASHBOARD ACTIVITY:<span class="important">*</span></strong></label>
                                    <select class="form-control select2" name="dashboard_activity_id" style="width:100%;">
                                        <option value="" disabled>-- Select Dashboard Activity -- </option>
                                            @foreach ($dashboard_activities as $dashboard_activity )
                                                @if($dashboard_activity)
                                                    <option value="{{ $dashboard_activity->id }}" @if($task->dashboard_activity_id == $dashboard_activity->id) selected @endif>{{ ucwords($dashboard_activity->name) }}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="client_activity_id" class="col-form-label custom-label"><strong>CLIENT ACTIVITY:<span class="important">*</span></strong></label>
                                    <select class="form-control select2" name="client_activity_id" style="width:100%;">
                                        <option value="" disabled>-- Select Client Activity -- </option>
                                            @foreach ($client_activities as $client_activity )
                                                @if($client_activity)
                                                    <option value="{{ $client_activity->id }}" @if($task->client_activity_id == $client_activity->id) selected @endif>{{ ucwords($client_activity->name) }}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="description" class="col-form-label custom-label"><strong>DESCRIPTION:<span class="important">*</span></strong></label>
                                    <textarea class="form-control" name="description">{!! $task->description !!}</textarea>
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
                                        <option value="" disabled selected>-- Select Status --</option>
                                        <option value="In Progress" @if($task->status == "In Progress") selected @endif>In Progress @if($task->status == "In Progress") (current) @endif</option>
                                        <option value="Completed" @if($task->status == "Completed") selected @endif>Completed @if($task->status == "Completed") (current) @endif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="start_date" class="col-form-label custom-label"><strong>START TIME:</span></strong></label>
                                    <input type="text" class="form-control" name="start_date" value="@isset($task->start_date){{ date('m/d/Y h:i:s A', strtotime($task->start_date)) }}@endisset" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="end_date" class="col-form-label custom-label"><strong>END TIME:</span></strong></label>
                                    <input type="text" class="form-control" name="end_date" value="@isset($task->end_date){{ date('m/d/Y h:i:s A', strtotime($task->end_date)) }}@endisset" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="actual_handling_time" class="col-form-label custom-label"><strong>ACTUAL HANDLING TIME:</span></strong></label>
                                    <input type="text" class="form-control" name="actual_handling_time" value="{{ $task->actual_handling_time }}" placeholder="00:00:00:00" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="volume" class="col-form-label custom-label"><strong>VOLUME:</span></strong></label>
                                    <input type="text" class="form-control" name="volume" value="{{ $task->volume }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="remarks" class="col-form-label custom-label"><strong>REMARKS:</span></strong></label>
                                    <textarea class="form-control" name="remarks" readonly>{!! $task->remarks !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="update('editTaskForm-{{ $task->id }}')"><i class="fa fa-save"></i> Update</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
