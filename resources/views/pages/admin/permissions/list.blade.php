@extends('layouts.master')

@section('title') Users List @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/fixedColumns.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
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
                            <a href="{{ url('HREmployeeProfileAPI') }}">
                                <button class="btn btn-primary waves-effect waves-light" title="Click to update the employees list in create user."><i class="fa fa-sync"></i> Sync HR Portal Employees</button>
                            </a>
                        </div>
                    </div>
                    <table id="tbl_permission" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Email Address</th>
                                <th>Cluster</th>
                                <th>Client</th>
                                <th>Team Leader</th>
                                <th>Operations Manager</th>
                                <th>Permission</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                    </table>
                    <div id="div-spinner" class="text-center mt-4 mb-4">
                        <span id="loader" style="font-size: 16px"><i class="fa fa-spinner fa-spin"></i> Please wait...</span>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>

    @include('pages.admin.permissions.add-modal')
    @include('pages.admin.permissions.edit-modal')
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.fixedColumns.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.js') }}"></script>
    <script>
        function getClientTLOMs()
        {
            var cluster_id = $('#cluster_id').val();
            if(cluster_id)
            {
                // load Clients
                $.ajax({
                    type: 'GET',
                    url: `{{ url('clients/get_clients/${cluster_id}') }}`,
                    dataType: 'json',
                    success: function(result){
                        console.log(result);
                        if(result.length > 0)
                                        {
                            $('#client_id').empty();
                            $('#client_id').append('<option value="">'+ '-- Select Client --' +'</option>');
                            $.each(result, function(index, value){
                                // console.log(value);
                                $('#client_id').append('<option value="'+ value.id +'">' + value.name +'</option>');
                            });

                        }
                        else
                        {
                            $('#client_id option[value=""]').prop('selected', true);
                        }

                    },

                    error: function(error) {
                        console.log(error);
                    }
                });

                // load TL / OM
                $.ajax({
                    type: 'GET',
                    url: `{{ url('permissions/get_tloms/${cluster_id}') }}`,
                    dataType: 'json',
                    success: function(result){
                        console.log(result);
                        if(result.length > 0)
                                        {
                            $('#tl_id').empty();
                            $('#tl_id').append('<option value="">'+ '-- Select Team Leader --' +'</option>');
                            $.each(result, function(index, value){
                                // console.log(value);
                                $('#tl_id').append('<option value="'+ value.user_id +'">' + value.fullname + ' ' + value.last_name +'</option>');
                            });

                            $('#om_id').empty();
                            $('#om_id').append('<option value="">'+ '-- Select Operations Manager --' +'</option>');
                            $.each(result, function(index, value){
                                // console.log(value);
                                $('#om_id').append('<option value="'+ value.user_id +'">' + value.fullname + ' ' + value.last_name +'</option>');
                            });

                        }
                        else
                        {
                            $('#tl_id option[value=""]').prop('selected', true);
                            $('#tom_id option[value=""]').prop('selected', true);
                        }

                    },

                    error: function(error) {
                        console.log(error);
                    }
                });
            }
            else
            {
                $('#tl_id option[value=""]').prop('selected', true);
                $('#tom_id option[value=""]').prop('selected', true);
                $('#client_id option[value=""]').prop('selected', true);
            }
        }

        function getClientTLOMsEdit()
        {
            var cluster_id = $('#cluster_id_edit').val();
            if(cluster_id)
            {
                // load Clients
                $.ajax({
                    type: 'GET',
                    url: `{{ url('clients/get_clients/${cluster_id}') }}`,
                    dataType: 'json',
                    success: function(result){
                        console.log(result);
                        if(result.length > 0)
                                        {
                            $('#client_id_edit').empty();
                            $('#client_id_edit').append('<option value="">'+ '-- Select Client --' +'</option>');
                            $.each(result, function(index, value){
                                // console.log(value);
                                $('#client_id_edit').append('<option value="'+ value.id +'">' + value.name +'</option>');
                            });

                        }
                        else
                        {
                            $('#client_id_edit option[value=""]').prop('selected', true);
                        }

                    },

                    error: function(error) {
                        console.log(error);
                    }
                });

                // load TL / OM
                $.ajax({
                    type: 'GET',
                    url: `{{ url('permissions/get_tloms/${cluster_id}') }}`,
                    dataType: 'json',
                    success: function(result){
                        console.log(result);
                        if(result.length > 0)
                                        {
                            $('#tl_id_edit').empty();
                            $('#tl_id_edit').append('<option value="">'+ '-- Select Team Leader --' +'</option>');
                            $.each(result, function(index, value){
                                // console.log(value);
                                $('#tl_id_edit').append('<option value="'+ value.user_id +'">' + value.fullname + ' ' + value.last_name +'</option>');
                            });

                            $('#om_id_edit').empty();
                            $('#om_id_edit').append('<option value="">'+ '-- Select Operations Manager --' +'</option>');
                            $.each(result, function(index, value){
                                // console.log(value);
                                $('#om_id_edit').append('<option value="'+ value.user_id +'">' + value.fullname + ' ' + value.last_name +'</option>');
                            });

                        }
                        else
                        {
                            $('#tl_id_edit option[value=""]').prop('selected', true);
                            $('#tom_id_edit option[value=""]').prop('selected', true);
                        }

                    },

                    error: function(error) {
                        console.log(error);
                    }
                });
            }
            else
            {
                $('#tl_id_edit option[value=""]').prop('selected', true);
                $('#tom_id_edit option[value=""]').prop('selected', true);
                $('#client_id_edit option[value=""]').prop('selected', true);
            }
        }
    </script>
@endsection

@section('custom-js')
    <script src="{{asset('scripts/permissions.js')}}"></script>
@endsection


