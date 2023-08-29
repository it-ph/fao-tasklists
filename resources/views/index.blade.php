@extends('layouts.master')

@section('title') Dashboard @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/fixedColumns.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') FAO Tasklists @endslot
        @slot('title') Dashboard @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card overflow-hidden">
                <div class="bg-primary bg-soft">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-white m-3">
                                <h5 class="text-white">Welcome Back, @isset(Auth::user()->employeeprofile) {{ Auth::user()->employeeprofile->fullname }} {{ Auth::user()->employeeprofile->last_name }} @endisset!</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <a href="{{ route("my-task.index", ['status' => "In Progress"]) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View In Progress Tasks">
                                        <p class="text-muted fw-medium">In Progress</p>
                                        <h4 class="mb-0">{{ number_format($in_progress) }}</h4>
                                    </a>
                                </div>

                                <div class="avatar-sm rounded-circle bg-success align-self-center mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-success">
                                        <i class="bx bx-task font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <a href="{{ route("my-task.index", ['status' => "On Hold"]) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View On Hold Tasks">
                                        <p class="text-muted fw-medium">On Hold</p>
                                        <h4 class="mb-0">{{ number_format($on_hold) }}</h4>
                                    </a>
                                </div>

                                <div class="avatar-sm rounded-circle bg-warning align-self-center mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-warning">
                                        <i class="bx bx-task font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <a href="{{ route("my-task.index", ['status' => "Completed"]) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View Completed Tasks">
                                        <p class="text-muted fw-medium">Completed</p>
                                        <h4 class="mb-0">{{ number_format($completed) }}</h4>
                                    </a>
                                </div>

                                <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bx-task font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <a href="{{ route("my-task.index") }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View All Tasks">
                                        <p class="text-muted fw-medium">Total Tasks</p>
                                        <h4 class="mb-0">{{ number_format($all) }}</h4>
                                    </a>
                                </div>

                                <div class="avatar-sm rounded-circle bg-secondary align-self-center mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-secondary">
                                        <i class="bx bx-task font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">My Latest Tasks</h4>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Status</th>
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
                                        <td>@isset($task->theagent->employeeprofile){{ $task->theagent->employeeprofile->fullname }} {{ $task->theagent->employeeprofile->last_name }}@endisset</td>
                                        <td>{{ date('m/d/Y', strtotime($task->shift_date)) }}</td>
                                        <td>{{ date('m/d/Y', strtotime($task->date_received)) }}</td>
                                        <td>{{ $task->thecluster->name }}</td>
                                        <td>{{ $task->theclient->name }}</td>
                                        {{-- <td>{{ $task->thedashboardactivity->name }}</td> --}}
                                        <td>{{ $task->theclientactivity->name }}</td>
                                        <td>{{ $task->description }}</td>
                                        <td>@isset($task->start_date){{ date('m/d/Y h:i:s A', strtotime($task->start_date)) }}@endisset</td>
                                        <td>@isset($task->end_date){{ date('m/d/Y h:i:s A', strtotime($task->end_date)) }} @else - @endisset</td>
                                        <td>
                                            @if($task->status == "On Hold")
                                                -
                                            @else
                                                @isset($task->end_date){{ date('m/d/Y', strtotime($task->end_date)) }} @endisset
                                            @endif
                                        </td>
                                        <td>{{ $task->actual_handling_time }}</td>
                                        <td>{{ $task->volume }}</td>
                                        <td>{{ $task->remarks }}</td>
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
    <!-- end row -->
@endsection
@section('script')
    <!-- apexcharts -->
    {{-- <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script> --}}

    <!-- dashboard init -->
    {{-- <script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script> --}}

    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.fixedColumns.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>

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
                    "order": [2, "desc"],
                    "columnDefs": [{ type: 'date', 'targets': [2] }],
                    fixedColumns: {
                        left: 3
                    },
                    "scrollX": true,
                });
            });
    </script>
@endsection
