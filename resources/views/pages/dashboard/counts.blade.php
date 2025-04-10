<div class="col-lg-8">
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <a href="{{ route("my-tasks.index", ['status'=> "In Progress"]) }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="View In Progress Tasks">
                                <p class="text-muted fw-medium">In Progress</p>
                                <h4 class="mb-0">{{ number_format($in_progress) }}</h4>
                            </a>
                        </div>

                        <div class="avatar-xs rounded-circle bg-success align-self-center mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-success">
                                <i class="bx bx-task font-size-18"></i>
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
                            <a href="{{ route("my-tasks.index", ['status'=> "On Hold"]) }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="View On Hold Tasks">
                                <p class="text-muted fw-medium">On Hold</p>
                                <h4 class="mb-0">{{ number_format($on_hold) }}</h4>
                            </a>
                        </div>

                        <div class="avatar-xs rounded-circle bg-warning align-self-center mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-warning">
                                <i class="bx bx-task font-size-18"></i>
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
                            <a href="{{ route("my-tasks.index", ['status'=> "Completed"]) }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="View Completed Tasks">
                                <p class="text-muted fw-medium">Completed</p>
                                <h4 class="mb-0">{{ number_format($completed) }}</h4>
                            </a>
                        </div>

                        <div class="avatar-xs rounded-circle bg-primary align-self-center mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="bx bx-task font-size-18"></i>
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
                            <a href="{{ route("my-tasks.index", ['status'=> "all"]) }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="View All Tasks">
                                <p class="text-muted fw-medium">Total Tasks</p>
                                <h4 class="mb-0">{{ number_format($all) }}</h4>
                            </a>
                        </div>

                        <div class="avatar-xs rounded-circle bg-secondary align-self-center mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-secondary">
                                <i class="bx bx-task font-size-18"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
