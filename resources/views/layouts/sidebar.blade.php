<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                {{-- MAIN NAVIGATION --}}
                <li class="menu-title" key="t-menu">Main Navigation</li>

        {{-- Start of Active Users --}}
                <li>
                    <a href="{{ url('home') }}" class="waves-effect">
                        <i class="bx bxs-dashboard"></i>
                        <span key="t-home">Dashboard</span>
                    </a>
                </li>

                {{-- <li>
                    <a href="{{ url('attendance') }}" class="waves-effect">
                        <i class="fa fa-clock" @if(\Request::routeIs('attendance')) style="color:#fff" @endif></i>
                        <span key="t-attendance" @if(\Request::routeIs('attendance')) style="color:#fff" @endif>Attendance</span>
                    </a>
                </li> --}}

                <li>
                    <a href="{{ url('activities') }}" class="waves-effect">
                        <i class="bx bx-list-ul" @if(\Request::routeIs('activities')) style="color:#fff" @endif></i>
                        <span key="t-activities" @if(\Request::routeIs('activities')) style="color:#fff" @endif>My Activities</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('my-tasks/all') }}" class="waves-effect">
                        <i class="bx bx-task" @if(\Request::routeIs('my-tasks.index')) style="color:#fff" @endif></i>
                        <span key="t-tasks" @if(\Request::routeIs('my-tasks.index')) style="color:#fff" @endif>My Tasks</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('assigned-tasks/all') }}" class="waves-effect">
                        <i class="bx bx-task" @if(\Request::routeIs('assigned-tasks.index')) style="color:#fff" @endif></i>
                        <span key="t-tasks" @if(\Request::routeIs('assigned-tasks.index')) style="color:#fff" @endif>Assigned Tasks</span>
                    </a>
                </li>

            @if(auth()->user()->isAccountant())
                {{--REPORTS --}}
                <li class="menu-title" key="t-menu">Reports</li>
                <li>
                    <a href="{{ url('reports') }}" class="waves-effect">
                        <i class="bx bxs-report"></i>
                        <span key="t-reports">Reports</span>
                    </a>
                </li>
                {{-- MANAGE --}}
                {{-- <li class="menu-title" key="t-apps">Manage</li> --}}
            @endif

        {{-- End of Active Users --}}

        {{-- Start of ADMIN / TL / OM --}}
            @if(auth()->user()->isTeamLeaderOrAdmin() || auth()->user()->isOperationsManagerOrAdmin())
                <li>
                    <a href="{{ url('tasks/?status=all') }}" class="waves-effect" @if(\Request::has('status')) style="color:#fff" @endif>
                        <i class="bx bx-data" @if(\Request::has('status')) style="color:#fff" @endif></i>
                        <span key="t-tasks-list">Task Lists</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('task-assignments/?tstatus=all') }}" class="waves-effect" @if(\Request::has('status')) style="color:#fff" @endif>
                        <i class="bx bx-user-check" @if(\Request::has('tstatus')) style="color:#fff" @endif></i>
                        <span key="t-tasks-list">Task Assignments</span>
                    </a>
                </li>

                {{--REPORTS --}}
                <li class="menu-title" key="t-menu">Reports</li>
                <li>
                    <a href="{{ url('reports') }}" class="waves-effect">
                        <i class="bx bxs-report"></i>
                        <span key="t-reports">Reports</span>
                    </a>
                </li>

                {{-- MANAGE --}}
                <li class="menu-title" key="t-apps">Manage</li>

                {{-- <li>
                    <a href="{{ url('permissions') }}" class="waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-users">Users</span>
                    </a>
                </li> --}}

                {{-- <li>
                    <a href="{{ url('activity-tracker') }}" class="waves-effect">
                        <i class="bx bx-pulse"></i>
                        <span key="t-users">Activity Tracker</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('idle-tracking') }}" class="waves-effect">
                        <i class="bx bxs-hourglass"></i>
                        <span key="t-users">Idle Tracking</span>
                    </a>
                </li> --}}

                <li>
                    <a href="{{ url('user-status') }}" class="waves-effect">
                        <i class="fa fa-users fa-sm"></i>
                        <span key="t-users">User Status</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('users') }}" class="waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-users">Users</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('clusters') }}" class="waves-effect">
                        <i class="bx bx-hive"></i>
                        <span key="t-clusters">Clusters</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('clients') }}" class="waves-effect">
                        <i class="bx bxs-group"></i>
                        <span key="t-clients">Clients</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('client-activities') }}@if(auth()->user()->isAccountant())/?user_id={{ auth()->user()->id }}&employeename=@isset(auth()->user()->employeeprofile){{ strtolower(auth()->user()->employeeprofile->fullname) }} {{ strtolower(auth()->user()->employeeprofile->last_name) }}@endisset @endif" class="waves-effect">
                        <i class="bx bx-list-ul" @if(\Request::has('employeename')) style="color:#fff" @endif></i>
                        <span key="t-client-activities" @if(\Request::has('employeename')) style="color:#fff" @endif>@if(auth()->user()->isTeamLeaderOrAdmin() || auth()->user()->isOperationsManagerOrAdmin()) Users' @endif Activities</span>
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ url('change-requests') }}" class="waves-effect">
                    <i class="bx bx-history"></i>
                    <span key="t-change-requests">Change Requests</span>
                </a>
            </li>

            @if(auth()->user()->isAdmin())
                <li>
                    <a href="{{ url('settings') }}" class="waves-effect">
                        <i class="bx bxs-cog"></i>
                        <span key="t-settings">Settings</span>
                    </a>
                </li>
            @endif

            </ul>
        {{-- End of ADMIN / TL / OM --}}
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
