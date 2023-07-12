<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />
        <title> FAO Tasklists | @yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSRF Token -->
        <meta content="FAO Web Based Tasklists" name="description" />
        <meta content="Rico Bugtong" name="author" />

        <!-- CSRF Token -->
        <meta name="_token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
        @include('layouts.head-css')
    </head>

    @yield('body')

    @yield('content')

    @include('layouts.vendor-scripts')
    </body>
</html>
