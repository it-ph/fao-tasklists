@extends('layouts.master')

@section('title') Users List @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Users @endslot
        @slot('title') Users List @endslot
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
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addPermissionModal"><i class="fas fa-plus"></i> Create</button>
                        </div>
                    </div>
                    <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Email Address</th>
                                <th>Cluster</th>
                                <th>Client</th>
                                <th>Team Lead</th>
                                <th>Operations Manager</th>
                                <th>Permission</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>@isset($permission->theuser->employeeprofile) {{ $permission->theuser->employeeprofile->fullname }} {{ $permission->theuser->employeeprofile->last_name }} @endisset</td>
                                    <td>@isset($permission->theuser->employeeprofile) {{ strtolower($permission->theuser->email) }} @endisset</td>
                                    <td>@isset($permission->thecluster) {{ $permission->thecluster->name }} @endisset</td>
                                    <td>@isset($permission->theclient) {{ $permission->theclient->name }} @endisset</td>
                                    <td>@isset($permission->thetl->theuser->employeeprofile) {{ $permission->thetl->theuser->employeeprofile->fullname }} {{ $permission->thetl->theuser->employeeprofile->last_name }} @endisset</td>
                                    <td>@isset($permission->theom->theuser->employeeprofile) {{ $permission->theom->theuser->employeeprofile->fullname }} {{ $permission->theom->theuser->employeeprofile->last_name }} @endisset</td>
                                    <td>{{ ucwords($permission->permission) }}</td>
                                    <td class="text-center">
                                        <form id="deletePermissionForm-{{ $permission->id }}" class="form-horizontal" action="{{ route('permissions.destroy',$permission) }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editPermissionModal-{{ $permission->id }}"><i class="fas fa-pencil-alt"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" onclick="idelete('deletePermissionForm-{{ $permission->id }}')"><i class="fas fa-times"></i></button>
                                        </form>

                                    </td>
                                </tr>
                                @include('pages.admin.permissions.edit-modal')
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div>

    @include('pages.admin.permissions.add-modal')
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.js') }}"></script>
@endsection
