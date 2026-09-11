@extends('layouts.master')

@section('title') Assigned Tasks @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/fixedColumns.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Assigned Tasks @endslot
        @slot('title') Assigned Tasks List - <span>{{ ucwords($status) }}</span>@endslot
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
                                <button type="button" class="btn btn-primary btn-sm waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false"><i class="fa fa-filter"></i> Filter <i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('assigned-tasks/all') }}">All Tasks</a>
                                    <a class="dropdown-item" href="{{ url('assigned-tasks/Not Started') }}">Not Started</a>
                                    <a class="dropdown-item" href="{{ url('assigned-tasks/In Progress') }}">In Progress</a>
                                    <a class="dropdown-item" href="{{ url('assigned-tasks/On Hold') }}">On Hold</a>
                                    <a class="dropdown-item" href="{{ url('assigned-tasks/Completed') }}">Completed</a>
                                </div>
                            </div>
                            <p id="status" style="display:none">{{ $status }}</p>
                            <p id="has_active_task" style="display:none">{{ auth()->user()->hasActiveTask() }}</p>
                            {{-- <span id="create_button">
                                @if(auth()->user()->hasActiveTask())
                                    <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" onclick="has_active_task()"><i class="fas fa-plus"></i> Create</button>
                                @else
                                    <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addTaskModal"><i class="fas fa-plus"></i> Create</button>
                                @endif
                            </span> --}}
                        </div>
                    </div>
                    <table id="tbl_task" class="table table-bordered table-striped table-sm nowrap w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Employee Name</th>
                                <th>Schedule</th>
                                <th>Cluster</th>
                                <th>Client</th>
                                <th>Activity Name</th>
                                <th>Applicable Month</th>
                                <th>Client Function</th>
                                <th>Eclerx Function</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Date Completed</th>
                                <th>Actual Handling Time</th>
                                <th>Timeliness</th>
                                <th>Quality</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    {{-- @include('pages.agent.tasks.add-modal') --}}
    @include('pages.agent.tasks-assigned.edit-modal')
    @include('pages.agent.tasks-assigned.start-modal')
    @include('pages.agent.tasks-assigned.stop-modal')
    @include('pages.agent.tasks-assigned.pause-modal')
    @include('pages.agent.tasks-assigned.resume-modal')
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.fixedColumns.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.js') }}"></script>
@endsection

@section('custom-js')
    <script src="{{asset('scripts/agent-task-assignments.js')}}"></script>
@endsection
