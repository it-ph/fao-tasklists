@yield('css')

<!-- Bootstrap Css -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- Custom Css-->
<link href="{{ asset('assets/css/custom.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- Sweet Alert-->
<link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

{{-- Datatable --}}
<style>
    .dataTables_scrollBody thead tr[role="row"]{
        visibility: collapse !important;
    }
    table.dataTable thead tr>.dtfc-fixed-left, table.dataTable thead tr>.dtfc-fixed-right, table.dataTable tfoot tr>.dtfc-fixed-left, table.dataTable tfoot tr>.dtfc-fixed-right {
        background: #00599D;
    }
</style>

