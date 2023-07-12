@extends('layouts.master')

@section('title') Client Activities List @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Users' Client Activities @endslot
        @slot('title') Users' Client Activities List @endslot
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
                            <a href="{{ url('client-activity-upload-template') }}" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download"></i> Template</a>
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#uploadClientActivityModal"><i class="fas fa-upload"></i> Upload</button>
                        </div>
                    </div>

                    <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Email Address</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>@isset($permission->theuser->employeeprofile) {{ $permission->theuser->employeeprofile->fullname }} {{ $permission->theuser->employeeprofile->last_name }} @endisset</td>
                                    <td>@isset($permission->theuser->employeeprofile) {{ strtolower($permission->theuser->email) }} @endisset</td>
                                    <td class="text-center">
                                        <a href="{{ url('client-activities') }}/?user_id={{ $permission->theuser->id }}&employeename={{ strtolower($permission->theuser->employeeprofile->fullname) }} {{ strtolower($permission->theuser->employeeprofile->last_name) }}" title="View Client Activities">
                                            <button class="btn btn-primary btn-sm"><span class="badge" style="background-color:#fff; color:#00599D">{{ $permission->theuser->theclientactivities->count() }}</span> View Client Activities <i class="fa fa-chevron-right"></i></button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div>

    @include('pages.admin.client-activities.upload-modal')
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endsection
