<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
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
            {{-- <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="bx bx-fullscreen text-white"></i>
                </button>
            </div> --}}

            <div class="dropdown d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" title="Set Shift Date" data-bs-toggle="modal" data-bs-target="#editShiftDate">
                    @if(auth()->user()->shift_date) <span class=" text-white">SHIFT DATE: {{ date('m/d/Y', strtotime(auth()->user()->shift_date)) }}</span> @endif <i class="bx bx-calendar text-white"></i>
                </button>
            </div>

            @if(auth()->user()->isOperationsManagerOrAdmin())
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-bell bx-tada text-white"></i>
                    <span class="badge bg-danger rounded-pill change_request_counts"></span>
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
                        <a href="{{ url('change-requests') }}" class="text-reset notification-item">
                            <div class="media">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-danger rounded-circle font-size-16">
                                        <i class="bx bx-task"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1" key="t-shipped">There are <strong><span class="change_request_counts"></span></strong></h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-grammer">New Change Requests</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif

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
                            <h5 class="name font-weight-bold mb-1">{{ auth()->user()->fullname }}</h5>
                            <p class="email text-muted mb-2">{{ auth()->user()->email }}</p>
                            <p class="email text-muted mb-1 fw-bold">{{ ucwords(auth()->user()->thecluster->name) }}</p>
                            <p class="email text-muted mb-1">{{ ucwords(auth()->user()->permission) }}</p>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a id="log-out" class="dropdown-item text-muted" href="#"><i class="bx bx-power-off font-size-16 align-middle me-1 text-muted"></i> <span key="t-logout">Logout</span></a>
                </div>
            </div>
        </div>
    </div>
</header>
