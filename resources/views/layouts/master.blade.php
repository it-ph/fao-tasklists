<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>FAO Tasklists | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="FAO Web Based Tasklists" name="description" />
    <meta content="Rico Bugtong" name="author" />

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="robots" content="noindex">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
    @include('layouts.head-css')
</head>

@section('body')
    <body data-sidebar="dark" data-keep-enlarged="true" class="vertical-collpsed">
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            @include('pages.agent.clock-in-out-modal')
            @include('pages.agent.edit-shift-date-modal')
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    @include('layouts.right-sidebar')
    <!-- /Right-bar -->

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')

    <!-- Base Url -->
    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!}
    </script>

    @yield('custom-js')
    <script>
        $(document).ready(function() {
            // Global Configuration Sheet
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right", // Applied application-wide
                "timeOut": "4000",
                "extendedTimeOut": "1000"
            };

            // Catch and route all Laravel validation and redirect flash packets
            // @if(session('success'))
            //     toastr.success("{{ session('success') }}");
            // @endif

            // @if(session('error'))
            //     toastr.error("{{ session('error') }}");
            // @endif

            // @if(session('info'))
            //     toastr.info("{{ session('info') }}");
            // @endif

            // @if(session('warning'))
            //     toastr.warning("{{ session('warning') }}");
            // @endif

            // // Dynamic Validation Errors Loop Handler
            // @if($errors->any())
            //     @foreach($errors->all() as $error)
            //         toastr.error("{{ $error }}", "Validation Error");
            //     @endforeach
            // @endif
        });
    </script>

    <script>
        toastr.options = {
            "positionClass": "toast-bottom-right",
        }

        $('#log-out').click(function(){
            Swal.fire({
                title: 'Sign Out?',
                text: "Are you sure you want to sign-out?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                confirmButtonClass: 'btn btn-primary mt-2',
                cancelButtonClass: 'btn btn-danger ms-2 mt-2',
                buttonsStyling: false,
                allowOutsideClick: false
            }).then(function(result) {
                if (result.value) {
                    Swal.fire({
                        title: 'Thank you!',
                        icon: 'success',
                        allowOutsideClick: false
                    });
                    window.location.href = "{{ URL::to('logout') }}"
                }
            });
        });

        $(document).ready(function () {
            refreshChangeRequestCount();
        });

        function refreshChangeRequestCount() {
            axios(`${APP_URL}/change-request/count`).then(function(response) {
                $('.change_request_counts').text(response.data.count);
                }).catch(error => {
                toastr.error(error);
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            // Reusable utility to add leading zeros
            const pad = num => String(num).padStart(2, '0');

            // 1. LIVE SYSTEM CLOCK
            const $clock = $('#liveClockDisplay');
            if ($clock.length) {
                setInterval(function () {
                    const now = new Date();
                    let hours = now.getHours();
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12 || 12; // Converts 0 to 12

                    $clock.text(`${pad(hours)}:${pad(now.getMinutes())}:${pad(now.getSeconds())} ${ampm}`);
                }, 1000);
            }

            // 2. LIVE DURATION TRACKER (Injected conditionally by Laravel)
            @if(auth()->user()->todayAttendance?->clock_in && !auth()->user()->todayAttendance?->clock_out)
                const clockInTime = new Date("{{ auth()->user()->todayAttendance->clock_in->toIso8601String() }}");
                const $duration = $('#liveDurationDisplay');

                if ($duration.length) {
                    function calculateDuration() {
                        const diffMs = new Date() - clockInTime;
                        if (diffMs <= 0) return;

                        const totalMinutes = Math.floor(diffMs / 1000 / 60);
                        const hours = Math.floor(totalMinutes / 60);
                        const minutes = totalMinutes % 60;

                        $duration.text(`${pad(hours)}h ${pad(minutes)}m`);
                    }

                    setInterval(calculateDuration, 60000); // Update every minute
                    calculateDuration(); // Run immediately on load
                }
            @endif
        });
    </script>

    <script>
        $(document).ready(function() {
            // Configure Toastr Settings
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "timeOut": "3000" // Disappears in 3 seconds
            };

            // Trigger Toast from Laravel Session Flash
            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
</body>

</html>
