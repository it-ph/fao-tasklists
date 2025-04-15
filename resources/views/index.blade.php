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

        <div class="col-xl-12">
            <div class="card overflow-hidden">
                <div class="bg-primary bg-soft">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-white m-3">
                                <h6 class="text-white">Welcome Back, {{ Auth::user()->fullname }} {{ Auth::user()->last_name }}!</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- @include('pages.dashboard.latest-tasks') --}}
    <div class="row">
        @include('pages.dashboard.filters')
        @include('pages.dashboard.counts')

    </div>
    <div class="row">
        {{-- @include('pages.dashboard.agents-fte')
        @include('pages.dashboard.clients-fte') --}}

        @include('pages.dashboard.fte')
        {{-- @include('pages.dashboard.fte-weekly')
        @include('pages.dashboard.fte-monthly') --}}
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
