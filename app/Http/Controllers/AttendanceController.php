<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function updateClockIO(Request $request)
    {
        $user = auth()->user();
        
        // Fetch today's record from your User relation setup
        $attendance = $user->todayAttendance;

        // STATE 1: User hasn't clocked in yet today
        if (!$attendance) {
            Attendance::create([
                'agent_id'   => $user->id,
                'shift_date' => Carbon::today()->toDateString(), // 2026-08-06
                'clock_in'   => Carbon::now(),
            ]);

            // Triggers toastr.success() via session flash keys
            return back()->with('success', 'You have successfully clocked in! Have a great shift.');
        }

        // STATE 2: User is clocked in and now needs to clock out
        if ($attendance->clock_in && !$attendance->clock_out) {
            $attendance->update([
                'clock_out' => Carbon::now(),
            ]);

            // Triggers toastr.success() via session flash keys
            return back()->with('success', 'You have successfully clocked out! See you tomorrow.');
        }

        // STATE 3: Guard condition if they try to double-click a finished shift
        return back()->with('error', 'Your shift for today has already been completed.');
    }
}
