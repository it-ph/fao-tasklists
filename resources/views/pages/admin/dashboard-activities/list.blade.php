@extends('layouts.master')

@section('title') Dashboard Activities List @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Dashboard Activities @endslot
        @slot('title') Dashboard Activities List @endslot
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
                            <a href="{{ url('dashboard-activity-upload-template') }}" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download"></i> Template</a>
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#uploadDashboardActivityModal"><i class="fas fa-upload"></i> Upload</button>
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addDashboardActivityModal"><i class="fas fa-plus"></i> Create</button>
                        </div>
                    </div>

                    <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Updated At</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($dashboard_activities as $dashboard_activity)
                                <tr>
                                    <td>{{ $dashboard_activity->name }}</td>
                                    <td>{{ date('m/d/Y h:i:s A', strtotime($dashboard_activity->updated_at)) }}</td>
                                    <td class="text-center">
                                        <form id="deleteDashboardActivityForm-{{ $dashboard_activity->id }}" class="form-horizontal" action="{{ route('dashboard-activities.destroy',$dashboard_activity) }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editDashboardActivityModal-{{ $dashboard_activity->id }}"><i class="fas fa-pencil-alt"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" onclick="idelete('deleteDashboardActivityForm-{{ $dashboard_activity->id }}')"><i class="fas fa-times"></i></button>
                                        </form>

                                    </td>
                                </tr>
                                @include('pages.admin.dashboard-activities.edit-modal')
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div>

    @include('pages.admin.dashboard-activities.add-modal')
    @include('pages.admin.dashboard-activities.upload-modal')
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endsection
