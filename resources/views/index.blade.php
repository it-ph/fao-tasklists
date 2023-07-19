@extends('layouts.master')

@section('title') Dashboard @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
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
                            <div class="text-white p-3">
                                <h5 class="text-white">Welcome Back to FAO Tasklists!</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-1">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="pt-4">
                                <h3 class="text-truncate">@isset(Auth::user()->employeeprofile) {{ Auth::user()->employeeprofile->fullname }} {{ Auth::user()->employeeprofile->last_name }} @endisset</h3>
                                <p class="text-muted mb-0 text-truncate">{{ ucwords(Auth::user()->thepermisssion->permission) }}</p>
                            </div>
                        </div>

                        <div class="col-sm-7">
                            <div class="pt-4">
                                <div class="row">
                                    <div class="col-6">
                                        <h3>{{ $in_progress }}</h3>
                                        <p class="text-muted mb-0">In Progress</p>
                                    </div>
                                    <div class="col-6">
                                        <h3>{{ $completed }}</h3>
                                        <p class="text-muted mb-0">Completed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-xl-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted fw-medium">Orders</p>
                                    <h4 class="mb-0">1,235</h4>
                                </div>

                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                    <span class="avatar-title">
                                        <i class="bx bx-copy-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted fw-medium">Revenue</p>
                                    <h4 class="mb-0">$35, 723</h4>
                                </div>

                                <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bx-archive-in font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-muted fw-medium">Average Price</p>
                                    <h4 class="mb-0">$16.2</h4>
                                </div>

                                <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> --}}
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">My Latest Tasks</h4>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Employee Name</th>
                                    <th>Shift Date</th>
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
                                        <td>@isset($task->theagent->employeeprofile){{ $task->theagent->employeeprofile->fullname }} {{ $task->theagent->employeeprofile->last_name }}@endisset</td>
                                        <td>{{ date('m/d/Y', strtotime($task->shift_date)) }}</td>
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
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- dashboard init -->
    <script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>

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
                    "order": [8, "desc"],
                    "columnDefs": [{ type: 'date', 'targets': [2] }],
                });
            });
    </script>
@endsection
