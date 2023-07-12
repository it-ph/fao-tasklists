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

                <li>
                    <a href="{{ url('my-task') }}" class="waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-tasks">My Tasks</span>
                    </a>
                </li>

        {{-- End of Active Users --}}

        {{-- End of ADMIN / TL / OM --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-tasks">Tasks</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ url('task') }}" key="t-tasks-list">Tasks List</a></li>
                        <li><a href="{{ url('tasks-upload') }}" key="t-upload-tasks">Upload Tasks</a></li>
                    </ul>
                </li>

                {{-- REPORTS --}}
                {{-- <li class="menu-title" key="t-apps">Reports</li>

                <li>
                    <a href="{{ url('reports') }}" class="waves-effect">
                        <i class="bx bxs-bar-chart-alt-2"></i>
                        <span key="t-reports">Reports</span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-bar-chart-alt-2"></i>
                        <span key="t-charts">Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="charts-apex" key="t-apex-charts">Apex Charts</a></li>
                        <li><a href="charts-echart" key="t-e-charts">E Charts</a></li>
                        <li><a href="charts-chartjs" key="t-chartjs-charts">Chartjs Charts</a></li>
                        <li><a href="charts-flot" key="t-flot-charts">Flot Charts</a></li>
                        <li><a href="charts-tui" key="t-ui-charts">Toast UI Charts</a></li>
                        <li><a href="charts-knob" key="t-knob-charts">Jquery Knob Charts</a></li>
                        <li><a href="charts-sparkline"
                                key="t-sparkline-charts">Sparkline Charts</a></li>
                    </ul>
                </li> --}}

                {{-- MANAGE --}}
                <li class="menu-title" key="t-apps">Manage</li>

                <li>
                    <a href="{{ url('permissions') }}" class="waves-effect">
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
                    <a href="{{ url('dashboard-activities') }}" class="waves-effect">
                        <i class="bx bx-list-ul"></i>
                        <span key="t-dashboard-activities">Dashboard Activities</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('client-activities') }}" class="waves-effect">
                        <i class="bx bx-list-ul"></i>
                        <span key="t-client-activities">Users' Client Activities</span>
                    </a>
                </li>
            </ul>
        {{-- End of ADMIN / TL / OM --}}
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
