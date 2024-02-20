@extends('layouts.master')

@section('title') Generate Reports  @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Reports @endslot
        @slot('title') Generate Reports @endslot
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
                    <form action="{{ route('export') }}" method="POST">
                        @csrf
                        <label class="mt-1"><strong>Filter Report</strong> <span style="font-weight: bold; color: red">*</span></label>
                        <div class="row">

                            @if(Auth::user()->isAccountant())
                                <div class="col-md-12">
                                    <input class="form-control input-daterange-datepicker" type="text" name="daterange" value="{{\Carbon\Carbon::now()->format('m-d-Y')}} - {{date('m-d-Y')}}">
                                </div>
                            @else
                                <input type="hidden" name="cluster_id" id="cluster_id" class="form-control" value="{{ Auth::user()->thepermisssion->cluster_id }}">
                                <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{ Auth::user()->thepermisssion->user_id }}">
                                <div class="col-md-3 mb-2">
                                    <input class="form-control input-daterange-datepicker" type="text" name="daterange" value="{{\Carbon\Carbon::now()->subDays(7)->format('m-d-Y')}} - {{date('m-d-Y')}}">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <select class="form-control select2" name="filter_by" id="filter_by">
                                        <option value="All" selected>All</option>
                                        <option value="Client">Client</option>
                                        <option value="Accountant">Accountant</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <select class="form-control select2" name="filtered_to[]" id="filtered_to" style="width:100%;" required>
                                        <option value="All" selected>All</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                        <button type="submit" data-toggle="tooltip" title="Click to Download Report" class="mt-2 btn btn-primary float-end"> <strong> <i class="fa fa-download"></i>  DOWNLOAD </strong></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('assets/plugins/moment/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/daterangepicker/daterangepicker.min.js')}}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.js') }}"></script>
    <script>
        $('.input-daterange-datepicker').daterangepicker({
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-danger'
        });

        // getClientAccountants
        $('#filter_by').change(function () {
            var filter_by = $('#filter_by').val();
            getFilterBy(filter_by);
        });

        function getFilterBy(filter_by)
        {
            if(filter_by == 'All')
            {
                $('#filtered_to').empty();
                $("#filtered_to").select2({
                    multiple: false,
                });
                $('#filtered_to').append('<option value="All">'+ 'All' +'</option>');
            }
            else if(filter_by == 'Client')
            {
                // load Clients
                var cluster_id = $('#cluster_id').val();
                $('#filtered_to').empty();
                $("#filtered_to").select2({
                    placeholder: 'Please wait...'
                });
                $.ajax({
                    type: 'GET',
                    url: `{{ url('clients/get_clients/${cluster_id}') }}`,
                    dataType: 'json',
                    success: function(result){
                        console.log(result);
                        if(result.length > 0)
                        {
                            $("#filtered_to").select2({
                                multiple: true,
                                closeOnSelect: false,
                                placeholder: '-- Select Client --'
                            });
                            $.each(result, function(index, value){
                                // console.log(value);
                                $('#filtered_to').append('<option value="'+ value.id +'">' + value.name +'</option>');
                            });

                        }
                        else
                        {
                            $('#filtered_to option[value=""]').prop('selected', true);
                        }

                    },

                    error: function(error) {
                        console.log(error);
                    }
                });
            }
            else if(filter_by == 'Accountant')
            {
                // load Accountants
                var user_id = $('#user_id').val();
                $('#filtered_to').empty();
                $("#filtered_to").select2({
                    placeholder: 'Please wait...'
                });
                $.ajax({
                    type: 'GET',
                    url: `{{ url('permissions/get_accountants/${user_id}') }}`,
                    dataType: 'json',
                    success: function(result){
                        console.log(result);
                        if(result.length > 0)
                        {
                            $("#filtered_to").select2({
                                multiple: true,
                                closeOnSelect: false,
                                placeholder: '-- Select Accountant --'
                            });
                            $.each(result, function(index, value){
                                // console.log(value);
                                $('#filtered_to').append('<option value="'+ value.user_id +'">' + value.fullname + ' ' + value.last_name +'</option>');
                            });

                        }
                        else
                        {
                            $('#filtered_to option[value=""]').prop('selected', true);
                        }

                    },

                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        }
    </script>
@endsection
