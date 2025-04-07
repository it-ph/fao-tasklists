<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">My Latest Tasks</h4>
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered table-sm nowrap w-100">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Employee Name</th>
                                <th>Shift Date</th>
                                <th>Date Received</th>
                                <th>Cluster</th>
                                <th>Client</th>
                                <th>Activity</th>
                                <th>Description</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Date Completed</th>
                                <th>Actual Handling Time</th>
                                <th>Volume</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $value)
                            <tr>
                                <td>
                                    @if($value->status == "In Progress")
                                    <span class="text-success"><strong>{{ $value->status }}</strong></span>
                                    @elseif($value->status == "On Hold")
                                    <span class="text-warning"><strong>{{ $value->status }}</strong></span>
                                    @elseif($value->status == "Completed")
                                    <span class="text-primary"><strong>{{ $value->status }}</strong></span>
                                    @endif
                                </td>
                                <td>@isset($value->theagent){{ $value->theagent->fullname }} {{
                                    $value->theagent->last_name }}@endisset</td>
                                <td>{{ date('Y-m-d', strtotime($value->shift_date)) }}</td>
                                <td>{{ date('Y-m-d', strtotime($value->date_received)) }}</td>
                                <td>{{ $value->thecluster->name }}</td>
                                <td>{{ $value->theclient->name }}</td>
                                <td>{{ $value->theclientactivity->name }}</td>
                                <td>{{ $value->description }}</td>
                                <td>@isset($value->start_date){{ date('Y-m-d h:i:s A', strtotime($value->start_date))
                                    }}@endisset</td>
                                <td>@isset($value->end_date){{ date('Y-m-d h:i:s A', strtotime($value->end_date)) }}
                                    @else - @endisset</td>
                                <td>@isset($value->end_date){{ date('Y-m-d', strtotime($value->end_date)) }} @else -
                                    @endisset</td>
                                <td>
                                    {{-- START OF ACTUAL HANDLING TIME --}}
                                    <?php
                                                $now = \Carbon\Carbon::now();
                                                $actual_handling_timer = $value->start_date->diff($now)->format('%D:%H:%I:%S');
                                                if($value->status <> "Completed")
                                                {
                                                    $start_at = $value->start_date;
                                                    $end_at = $now->format('Y-m-d H:i:s');
                                                    $shift_start = '00:00:00';
                                                    $shift_end = '23:59:59';
                                                    $pauses = [];
                                                    $events = []; //retain as empty array since there is no events module in the system

                                                    $pauses = $TimeElapsedHelper->getTaskPauses($value->id);
                                                    $working_hours = $TimeElapsedHelper->calculateWorkingTime($start_at, $end_at, $shift_start, $shift_end, $pauses,$events);
                                                    $actual_handling_time = $TimeElapsedHelper->convertTime($working_hours);
                                                }
                                                else
                                                {
                                                    $actual_handling_time = $value->actual_handling_time ? $value->actual_handling_time : $actual_handling_timer;
                                                }
                                            ?>
                                    {{ $actual_handling_time }}
                                    {{-- END OF ACTUAL HANDLING TIME --}}
                                </td>
                                <td>{{ $value->volume }}</td>
                                <td>{{ $value->remarks }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- end table-responsive -->
            </div>
        </div>
    </div>
</div>
