@extends('layouts.master')

@section('title') Task Assignments @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/fixedColumns.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .dataTables_scrollBody thead tr[role="row"]{
            visibility: collapse !important;
        }
    </style>
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Task Assignments @endslot
        @slot('title') Task Assignments List - @if(\Request::get('status')) <span>{{ ucwords(\Request::get('status')) }} @else ALL @endif</span>@endslot
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
                                    <a class="dropdown-item" href="{{ route("task-assignments.index", ['tstatus' => "all"]) }}">All Tasks</a>
                                    <a class="dropdown-item" href="{{ route("task-assignments.index", ['tstatus' => "Not Started"]) }}">Not Started</a>
                                    <a class="dropdown-item" href="{{ route("task-assignments.index", ['tstatus' => "In Progress"]) }}">In Progress</a>
                                    <a class="dropdown-item" href="{{ route("task-assignments.index", ['tstatus' => "On Hold"]) }}">On Hold</a>
                                    <a class="dropdown-item" href="{{ route("task-assignments.index", ['tstatus' => "Completed"]) }}">Completed</a>
                                </div>
                            </div>
                            <button type="button" id="btn_export" class="btn btn-primary btn-sm waves-effect waves-light"><i class="fas fa-download"></i> Template</button>
                            <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" onclick="TASK.showUploadModal()"><i class="fas fa-upload"></i> Upload</button>
                        </div>
                    </div>
                    <p id="status" style="display:none">@if(\Request::get('tstatus')) {{ (\Request::get('tstatus')) }} @else all @endif</p>
                    <p id="permission" class="ihide">{{ auth()->user()->isOperationsManagerOrAdmin() }}</p>
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
    @include('pages.admin.task-assignments.edit-modal')
    @include('pages.admin.task-assignments.upload-modal')
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
    <script src="{{asset('scripts/all-task-assignments.js')}}"></script>
@endsection
