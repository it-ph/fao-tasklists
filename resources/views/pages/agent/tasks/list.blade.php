@extends('layouts.master')

@section('title') Task Lists @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/fixedColumns.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Tasks @endslot
        @slot('title') Tasks List - @if(\Request::get('status')) <span>{{ ucwords(\Request::get('status')) }} @else ALL @endif</span>@endslot
    @endcomponent

    <div class="row">
        <div class="col-md-12">
            @include('notifications.success')
            @include('notifications.error')
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false"><i class="fa fa-filter"></i> Filter <i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route("my-task.index", ['status' => "all"]) }}">All Tasks</a>
                                    <a class="dropdown-item" href="{{ route("my-task.index", ['status' => "In Progress"]) }}">In Progress</a>
                                    <a class="dropdown-item" href="{{ route("my-task.index", ['status' => "On Hold"]) }}">On Hold</a>
                                    <a class="dropdown-item" href="{{ route("my-task.index", ['status' => "Completed"]) }}">Completed</a>
                                </div>
                            </div>
                            @if(Auth::user()->hasActiveTask())
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="has_active_task()"><i class="fas fa-plus"></i> Create</button>
                            @else
                                <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addTaskModal"><i class="fas fa-plus"></i> Create</button>
                            @endif
                        </div>
                    </div>
                    <table id="datatable" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Employee Name</th>
                                <th>Shift Date</th>
                                <th>Date Received</th>
                                <th>Cluster</th>
                                <th>Client</th>
                                {{-- <th>Dashboard Activity</th> --}}
                                <th>Client Activity</th>
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
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>
                                        @if($task->status == "In Progress")
                                            <span class="text-success"><strong>{{ $task->status }}</strong></span>
                                        @elseif($task->status == "On Hold")
                                            <span class="text-warning"><strong>{{ $task->status }}</strong></span>
                                        @elseif($task->status == "Completed")
                                            <span class="text-primary"><strong>{{ $task->status }}</strong></span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{-- Accessible by assigned AGENT only --}}
                                        @if($task->agent_id == Auth::id())
                                            {{-- STATUS: In Progress --}}
                                            @if($task->status == "In Progress")
                                                <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" data-bs-toggle="modal" data-bs-target="#editTaskModal-{{ $task->id }}"><i class="fas fa-pencil-alt"></i></button>
                                                {{-- <form id="pauseTaskForm-{{ $task->id }}" action="{{ route('task.pause',$task) }}" method="POST" style="display: none">
                                                    @csrf
                                                    @method("PUT")
                                                </form>
                                                <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" onclick="pause('pauseTaskForm-{{ $task->id }}')"><i class="fa fa-pause"></i></button> --}}
                                                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Stop Task: On Hold / Complete" data-bs-toggle="modal" data-bs-target="#stopTaskModal-{{ $task->id }}"><i class="fas fa-stop"></i></button>
                                            {{-- @elseif($task->status == "On Hold")
                                                <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editTaskModal-{{ $task->id }}"><i class="fas fa-pencil-alt"></i></button>
                                                <form id="resumeTaskForm-{{ $task->id }}" action="{{ route('task.resume',$task) }}" method="POST" style="display: none">
                                                    @csrf
                                                    @method("PUT")
                                                </form>
                                                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" onclick="resume('resumeTaskForm-{{ $task->id }}')"><i class="fa fa-play"></i></button> --}}
                                            @else
                                                -
                                            @endif
                                        @endif
                                    </td>
                                    <td>@isset($task->theagent->employeeprofile){{ $task->theagent->employeeprofile->fullname }} {{ $task->theagent->employeeprofile->last_name }}@endisset</td>
                                    <td>{{ date('m/d/Y', strtotime($task->shift_date)) }}</td>
                                    <td>{{ date('m/d/Y', strtotime($task->date_received)) }}</td>
                                    <td>{{ $task->thecluster->name }}</td>
                                    <td>{{ $task->theclient->name }}</td>
                                    {{-- <td>{{ $task->thedashboardactivity->name }}</td> --}}
                                    <td>{{ $task->theclientactivity->name }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>@isset($task->start_date){{ date('m/d/Y h:i:s a', strtotime($task->start_date)) }}@endisset</td>
                                    <td>@isset($task->end_date){{ date('m/d/Y h:i:s a', strtotime($task->end_date)) }} @else - @endisset</td>
                                    <td>
                                        @if($task->status == "On Hold")
                                            -
                                        @else
                                            @isset($task->end_date){{ date('m/d/Y', strtotime($task->end_date)) }} @else -  @endisset
                                        @endif
                                    </td>
                                    <td>{{ $task->actual_handling_time }}</td>
                                    <td>{{ $task->volume }}</td>
                                    <td>{{ $task->remarks }}</td>
                                </tr>

                                {{-- load only if task is In Progress --}}
                                @if($task->status == "In Progress")
                                    @include('pages.agent.tasks.edit-modal')
                                    {{-- @include('pages.agent.tasks.pause-modal') --}}
                                    @include('pages.agent.tasks.stop-modal')
                                {{-- @elseif($task->status == "On Hold")
                                    @include('pages.agent.tasks.edit-modal')
                                    @include('pages.agent.tasks.resume-modal') --}}
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    @include('pages.agent.tasks.add-modal')
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.fixedColumns.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    {{-- <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script> --}}
    <!-- Select2 -->
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.js') }}"></script>
    <script>
        $(document).ready(function() {
                $('#datatable').DataTable({
                    language: {
                        // search: '_INPUT_',
                        // searchPlaceholder: 'Search',
                        oPaginate: {
                            sNext: '<i class="fa fa-forward"></i>',
                            sPrevious: '<i class="fa fa-backward"></i>',
                            sFirst: '<i class="fa fa-step-backward"></i>',
                            sLast: '<i class="fa fa-step-forward"></i>'
                        },
                    },
                    "pageLength": 10,
                    "pagingType": "full_numbers",
                    "order": [3, "desc"],
                    "columnDefs": [{ type: 'date', 'targets': [3] }],
                    fixedColumns: {
                        left: 4
                    },
                    "scrollX": true,
                });
            });
    </script>
@endsection
