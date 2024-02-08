@extends('layouts.master')

@section('title') My Activity List @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') My Activities @endslot
        @slot('title') My Activity List @endslot
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
                    {{-- <div class="row mb-3">
                        <div class="col-md-12">
                            @if(Auth::user()->isAccountant())
                                <a href="{{ url('client-activity-upload-template') }}" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download"></i> Template</a>
                                <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#uploadClientActivityModal"><i class="fas fa-upload"></i> Upload</button>
                                <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addClientActivityModal"><i class="fas fa-plus"></i> Create</button>
                            @else
                                <a href="{{ url('client-activities') }}" class="btn btn-primary waves-effect waves-light"><i class="fas fa-chevron-left"></i> Back</a>
                                <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addClientActivityModal"><i class="fas fa-plus"></i> Create</button>
                            @endif
                        </div>
                    </div> --}}

                    <table id="datatable" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Frequency</th>
                                <th>Schedule</th>
                                <th>Function</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($client_activities as $client_activity)
                                <tr>
                                    <td>{{ ucfirst($client_activity->name) }}</td>
                                    <td>{{ ucfirst($client_activity->frequency) }}</td>
                                    <td>{{ ucfirst($client_activity->schedule) }}</td>
                                    <td>{{ ucfirst($client_activity->function) }}</td>
                                    <td class="text-center">
                                        {{-- <form id="deleteClientActivityForm-{{ $client_activity->id }}" class="form-horizontal" action="{{ route('client-activities.destroy',$client_activity) }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editClientActivityModal-{{ $client_activity->id }}"><i class="fas fa-pencil-alt"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" onclick="idelete('deleteClientActivityForm-{{ $client_activity->id }}')"><i class="fas fa-times"></i></button>
                                        </form> --}}
                                        <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editClientActivityModal-{{ $client_activity->id }}"><i class="fas fa-pencil-alt"></i></button>
                                    </td>
                                </tr>
                                @include('pages.admin.client-activities.edit-modal')
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div>

    {{-- @include('pages.admin.client-activities.upload-modal') --}}
    {{-- @include('pages.admin.client-activities.add-modal') --}}
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
