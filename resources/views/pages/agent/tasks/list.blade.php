@extends('layouts.master')

@section('title') Task Lists @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/fixedColumns.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Tasks @endslot
        @slot('title') Tasks List - <span>{{ ucwords($status) }}</span>@endslot
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
                                    <a class="dropdown-item" href="{{ url('my-tasks/all') }}">All Tasks</a>
                                    <a class="dropdown-item" href="{{ url('my-tasks/In Progress') }}">In Progress</a>
                                    <a class="dropdown-item" href="{{ url('my-tasks/On Hold') }}">On Hold</a>
                                    <a class="dropdown-item" href="{{ url('my-tasks/Completed') }}">Completed</a>
                                </div>
                            </div>
                            <p id="status" style="display:none">{{ $status }}</p>
                            <span id="create_button">
                                @if(Auth::user()->hasActiveTask())
                                    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="has_active_task()"><i class="fas fa-plus"></i> Create</button>
                                @else
                                    <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addTaskModal"><i class="fas fa-plus"></i> Create</button>
                                @endif
                            </span>
                        </div>
                    </div>
                    <table id="tbl_task" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Action</th>
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
                            <tr>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                                <th><input type="text" class="form-control"/></th>
                            </tr>
                        </thead>
                    </table>
                    <div id="div-spinner" class="text-center mt-4 mb-4">
                        <span id="loader" style="font-size: 16px"><i class="fa fa-spinner fa-spin"></i> Please wait...</span>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    @include('pages.agent.tasks.add-modal')
    @include('pages.agent.tasks.edit-modal')
    @include('pages.agent.tasks.stop-modal')
    @include('pages.agent.tasks.pause-modal')
    @include('pages.agent.tasks.resume-modal')
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
    <script src="{{asset('scripts/tasks.js')}}"></script>
    <script>
        $('#client_activity_id').change(function () {
            var text = $('#client_activity_id option:selected').text();
            $('#description').val(text);
        });
    </script>
@endsection
