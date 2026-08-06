<div class="col-lg-8">
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <a href="{{ route("my-tasks.index", ['status'=> "Sessions Today"]) }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="View Sessions Today">
                                <p class="text-muted fw-medium">Sessions Today</p>
                                <h4 class="mb-0">{{ number_format(23) }}</h4>
                            </a>
                        </div>

                        <div class="avatar-xs rounded-circle bg-danger align-self-center mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-danger">
                                <i class="bx bx-moon font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <a href="{{ route("my-tasks.index", ['status'=> "Screen Lock"]) }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="View Screen Lock">
                                <p class="text-muted fw-medium">Screen Lock</p>
                                <h4 class="mb-0">{{ number_format(23) }}</h4>
                            </a>
                        </div>

                        <div class="avatar-xs rounded-circle bg-secondary align-self-center mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-secondary">
                                <i class="bx bx-lock font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <a href="{{ route("my-tasks.index", ['status'=> "App Switch"]) }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="View App Switch">
                                <p class="text-muted fw-medium">App Switch</p>
                                <h4 class="mb-0">{{ number_format(23) }}</h4>
                            </a>
                        </div>

                        <div class="avatar-xs rounded-circle bg-primary align-self-center mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="bx bx-window font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <a href="{{ route("my-tasks.index", ['status'=> "No Interaction"]) }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="View No Interaction">
                                <p class="text-muted fw-medium">No Interaction</p>
                                <h4 class="mb-0">{{ number_format(23) }}</h4>
                            </a>
                        </div>

                        <div class="avatar-xs rounded-circle bg-warning align-self-center mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-warning">
                                <i class="bx bx-mouse font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
