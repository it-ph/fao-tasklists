@extends('layouts.master')

@section('title') Task Lists @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
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
                                    <a class="dropdown-item" href="{{ route("my-task.index", ['status' => "Completed"]) }}">Completed</a>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addTaskModal"><i class="fas fa-plus"></i> Create</button>
                        </div>
                    </div>
                    <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Employee Name</th>
                                <th>Date Received</th>
                                <th>Cluster</th>
                                <th>Client</th>
                                <th>Dashboard Activity</th>
                                <th>Client Activity</th>
                                <th>Description</th>
                                <th>Start Date</th>
                                <th>End Date</th>
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
                                        @elseif($task->status == "Completed")
                                            <span class="text-primary"><strong>{{ $task->status }}</strong></span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{-- Accessible by assigned AGENT only --}}
                                        @if($task->agent_id == Auth::id())
                                            {{-- STATUS: In Progress --}}
                                            @if($task->status == "In Progress")
                                                <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editTaskModal-{{ $task->id }}"><i class="fas fa-pencil-alt"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#stopTaskModal-{{ $task->id }}"><i class="fas fa-stop"></i></button>
                                            @else
                                                -
                                            @endif
                                        @endif
                                    </td>
                                    <td>@isset($task->theagent->employeeprofile){{ $task->theagent->employeeprofile->fullname }} {{ $task->theagent->employeeprofile->last_name }}@endisset</td>
                                    <td>{{ date('m/d/Y', strtotime($task->date_received)) }}</td>
                                    <td>{{ $task->thecluster->name }}</td>
                                    <td>{{ $task->theclient->name }}</td>
                                    <td>{{ $task->thedashboardactivity->name }}</td>
                                    <td>{{ $task->theclientactivity->name }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>@isset($task->start_date){{ date('m/d/Y h:i:s A', strtotime($task->start_date)) }}@endisset</td>
                                    <td>@isset($task->end_date){{ date('m/d/Y h:i:s A', strtotime($task->end_date)) }}@endisset</td>
                                    <td>{{ $task->actual_handling_time }}</td>
                                    <td>{{ $task->volume }}</td>
                                    <td>{{ $task->remarks }}</td>
                                </tr>

                                {{-- load only if task is In Progress --}}
                                @if($task->status == "In Progress")
                                    @include('pages.agent.tasks.stop-modal')
                                    @include('pages.agent.tasks.edit-modal')
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
                    "order": [9, "desc"],
                    "columnDefs": [{ type: 'date', 'targets': [2] }],
                    // orderCellsTop: true,
                    // fixedHeader: true,
                    // "scrollX": true,
                });
            });
    </script>
@endsection
