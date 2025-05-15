@extends('layouts.master')

@section('title') Change Requests List @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Change Requests @endslot
        @slot('title') Change Requests List @endslot
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
                            <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addChangeRequestModal"><i class="fas fa-plus"></i> Create</button>
                        </div>
                    </div>
                    <table id="tbl_change_request" class="table table-bordered table-striped table-sm nowrap w-100">
                        <thead>
                            <tr>
                                <th>Task ID</th>
                                <th>Employee Name</th>
                                <th>Cluster</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Closed At</th>
                                <th>Closed By</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div>

    @include('pages.admin.change-requests.add-modal')
    @include('pages.admin.change-requests.edit-modal')
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.js') }}"></script>
@endsection

@section('custom-js')
    <script src="{{asset('scripts/change-requests.js')}}"></script>
@endsection
