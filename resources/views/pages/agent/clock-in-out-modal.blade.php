<div class="modal fade" id="clockIO" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="clockIOModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clockIOModalLabel">Today's Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Modal Main Content Area Container -->
                <div class="modal-body text-center py-4">
                    @php
                        $attendance = auth()->user()->todaysAttendance;
                        // Check if the clock out happened more than 12 hours ago
                        $isPast12Hours = $attendance && $attendance->clock_out && $attendance->clock_out->lessThan(now()->subHours(12));
                    @endphp

                    @if($attendance && $attendance->clock_out && !$isPast12Hours)
                        <!-- If Shift Completed within 12 hours: Freeze display to show the exact Clock Out timestamp -->
                        <h1 class="display-7 fw-bold text-danger mb-0">
                            {{ $attendance->clock_out->format('h:i A') }}
                        </h1>
                    @elseif($attendance && $attendance->clock_in && !$isPast12Hours)
                        <!-- If Currently Working: Freeze display to show the exact Clock In timestamp -->
                        <h1 class="display-7 fw-bold text-success mb-0">
                            {{ $attendance->clock_in->format('h:i A') }}
                        </h1>
                    @else
                        <!-- If Not Clocked In Yet OR clock out > 12 hours: Render live ticking clock -->
                        <h1 id="liveClockDisplay" class="display-7 fw-bold text-success mb-0">00:00:00 AM</h1>
                    @endif
                    
                    <!-- Dynamic Status Subtitle Tracking Block -->
                    <p class="text-muted fw-semibold mb-4">
                        @if($attendance && $attendance->clock_in && !$attendance->clock_out)
                            Clocked In
                        @elseif($attendance && $attendance->clock_out && !$isPast12Hours)
                            Clocked Out
                        @else
                            Not Clocked In Yet
                        @endif
                    </p>

                    <!-- Shift Working Active Duration Counter Panel -->
                    @if($attendance && $attendance->clock_in && !$attendance->clock_out)
                        <div class="bg-light rounded p-3 mb-2">
                            <span class="text-muted d-block mb-1 fw-medium">Duration</span>
                            <h3 id="liveDurationDisplay" class="fw-bold text-dark mb-0">00h 00m</h3>
                        </div>
                    @elseif($attendance && $attendance->clock_in && $attendance->clock_out && !$isPast12Hours)
                        <!-- Fixed Final Total Working Hours Panel when Shift is Completed within 12 hours -->
                        <div class="bg-light rounded p-3 mb-2">
                            <span class="text-muted d-block mb-1 fw-medium">Total Working Hours</span>
                            <h3 class="fw-bold text-dark mb-0">
                                {{ sprintf('%02dh %02dm', 
                                    $attendance->clock_in->diffInHours($attendance->clock_out), 
                                    $attendance->clock_in->diffInMinutes($attendance->clock_out) % 60) 
                                }}
                            </h3>
                        </div>
                    @endif

                    <!-- Hidden Form Wrapper Managing Route Call Actions -->
                    <form id="clockIOForm" action="{{ route('clock-io.update') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                
                <!-- Modal Actions Control Footer Bar -->
                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                    @if(!$attendance || !$attendance->clock_in || $isPast12Hours)
                        <!-- State A: Triggers Clock In Request Logic (No record OR older than 12 hours) -->
                        <button type="button" class="btn btn-success w-100 py-2.5 fw-bold rounded-pill shadow-sm" onclick="clockIO('clockIOForm')">
                            <i class="bx bx-log-in me-1"></i> Clock IN
                        </button>
                    @elseif(!$attendance->clock_out)
                        <!-- State B: Triggers Clock Out Request Logic -->
                        <button type="button" class="btn btn-danger w-100 py-2.5 fw-bold rounded-pill shadow-sm" onclick="clockIO('clockIOForm')">
                            <i class="bx bx-log-out me-1"></i> Clock OUT
                        </button>
                    @else
                        <!-- State C: Clocked out within the 12-hour cooling window -->
                        <button type="button" class="btn btn-secondary w-100 py-2.5 fw-bold rounded-pill" disabled>
                            Shift Completed
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
