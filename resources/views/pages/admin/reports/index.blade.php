@extends('layouts.master')

@section('title') Generate Reports  @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}" />
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
                        <label class="mt-1"><strong>Date Range</strong> <span style="font-weight: bold; color: red">*</span></label>
                        @if(Auth::user()->isAccountant())
                            <input class="form-control input-daterange-datepicker" type="text" name="daterange" value="{{\Carbon\Carbon::now()->format('m-d-Y')}} - {{date('m-d-Y')}}">
                        @else
                            <input class="form-control input-daterange-datepicker" type="text" name="daterange" value="{{\Carbon\Carbon::now()->subDays(7)->format('m-d-Y')}} - {{date('m-d-Y')}}">
                        @endif
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
    <script>
        $('.input-daterange-datepicker').daterangepicker({
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-danger'
        });
    </script>
@endsection
