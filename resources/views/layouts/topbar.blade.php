<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                {{-- <a href="{{ url('home') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('images/logo.png') }}" alt="" height="23">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="" height="23">
                        <span style="color:#fff; font-size: 14px">FAO TASKLISTS</span>
                    </span>
                </a> --}}

                <a href="{{ url('home') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-white.png') }}" alt="" height="23">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-white.png') }}" alt="" height="23">
                        <span style="color:#fff; font-size: 14px">FAO TASKLISTS</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect text-white" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="d-flex">
            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="bx bx-fullscreen text-white"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-bell bx-tada text-white"></i>
                    <span class="badge bg-danger rounded-pill">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0" key="t-notifications">Notifications </h6>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="" class="text-reset notification-item">
                            <div class="media">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-secondary rounded-circle font-size-16">
                                        <i class="bx bx-task"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1" key="t-your-order">There are <strong>23</strong></h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-grammer">Not Started Tasks</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="media">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-warning rounded-circle font-size-16">
                                        <i class="bx bx-task"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1" key="t-your-order">There are <strong>23</strong></h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-grammer">In Progress - Without Issue</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="media">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-danger rounded-circle font-size-16">
                                        <i class="bx bx-task"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1" key="t-shipped">There are <strong>23</strong></h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-grammer">In Progress - With Issue</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center text-primary" href="javascript:void(0)">
                            <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">View All</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/user2-160x1601.png') }}"
                        alt="Header Avatar">
                    <i class="d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="dropdown-header d-flex flex-column align-items-center">
                        <div class="figure mb-2">
                            <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/user2-160x1601.png') }}">
                        </div>
                        <div class="text-center">
                            <h5 class="name font-weight-bold mb-1">@isset(Auth::user()->employeeprofile) {{ Auth::user()->employeeprofile->fullname }} {{ Auth::user()->employeeprofile->last_name }} @endisset</h5>
                            <p class="email text-muted mb-2">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-muted" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1 text-muted"></i> <span key="t-logout">Logout</span></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
