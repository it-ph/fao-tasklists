@extends('layouts.master')

@section('title') Clusters List @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Clusters @endslot
        @slot('title') Clusters List @endslot
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
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addClusterModal"><i class="fas fa-plus"></i> Create</button>
                        </div>
                    </div>

                    <table id="datatable" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Updated At</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($clusters as $cluster)
                                <tr>
                                    <td>{{ $cluster->name }}</td>
                                    <td>{{ date('m/d/Y h:i:s A', strtotime($cluster->updated_at)) }}</td>
                                    <td class="text-center">
                                        <form id="deleteClusterForm-{{ $cluster->id }}" class="form-horizontal" action="{{ route('clusters.destroy',$cluster) }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editClusterModal-{{ $cluster->id }}"><i class="fas fa-pencil-alt"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" onclick="idelete('deleteClusterForm-{{ $cluster->id }}')"><i class="fas fa-times"></i></button>
                                        </form>

                                    </td>
                                </tr>
                                @include('pages.admin.clusters.edit-modal')
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div>

    @include('pages.admin.clusters.add-modal')
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endsection
