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
                                <div class="col-md-6 mb-2">
                                    <input class="form-control input-daterange-datepicker" type="text" name="daterange" value="{{\Carbon\Carbon::now()->format('m-d-Y')}} - {{date('m-d-Y')}}">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <select class="form-control" name="f_filter" id="f_filter">
                                        <option value="All">All</option>
                                        <option value="Client">Client</option>
                                        <option value="Accountant">Accountant</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <select class="form-control select2" name="s_filter" id="s_filter" style="width:100%;" onchange="getClientTLOMs()">
                                        <option value="All">All</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                        <button type="submit" data-toggle="tooltip" title="Click to Download Report" class="mt-3 btn btn-primary float-end"> <strong> <i class="fa fa-download"></i>  DOWNLOAD </strong></button>
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

    </script>
@endsection
