@extends('layouts.master')
@inject('TimeElapsedHelper','App\Http\Helpers\TimeElapsedHelper')

@section('title') Dashboard @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/fixedColumns.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') FAO Tasklists @endslot
        @slot('title') Dashboard @endslot
    @endcomponent

    {{-- <div class="row"> --}}
        {{-- <div class="col-xl-12">
            <div class="card overflow-hidden">
                <div class="bg-primary bg-soft">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-white m-3">
                                <h5 class="text-white">Welcome Back, {{ Auth::user()->fullname }} {{ Auth::user()->last_name }}!</h5>
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
                                    <a href="{{ route("my-tasks.index", ['status' => "In Progress"]) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View In Progress Tasks">
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
                                    <a href="{{ route("my-tasks.index", ['status' => "On Hold"]) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View On Hold Tasks">
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
                                    <a href="{{ route("my-tasks.index", ['status' => "Completed"]) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View Completed Tasks">
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
                                    <a href="{{ route("my-tasks.index", ['status' => "all"]) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View All Tasks">
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
        </div> --}}
    {{-- </div> --}}

    {{-- @include('pages.dashboard.latest-tasks') --}}
    @include('pages.dashboard.filters')
    <div class="row">
        @include('pages.dashboard.agents-fte')
        @include('pages.dashboard.clients-fte')
    </div>
@endsection
@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.fixedColumns.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>

    {{-- <script>
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
                    dom: 'Bfrtip',
                    buttons: [
                        'excel'
                    ],
                    "pageLength": 20,
                    "pagingType": "full_numbers",
                    "order": [2, "desc"],
                    "columnDefs": [{ type: 'date', 'targets': [2] }],
                    fixedColumns: {
                        left: 3
                    },
                    "scrollX": true,
                });
            });
    </script> --}}
@endsection

@section('custom-js')
    <script src="{{asset('scripts/dashboard.js')}}"></script>
@endsection
