<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Task;
use App\Models\TaskPause;
use App\Models\TaskAssignment;
use App\Models\TaskAssignmentPause;

class AttendanceController extends Controller
{
    public function updateClockIO(Request $request)
    {
        $user = auth()->user();
        
        // Fetch today's record
        $attendance = $user->todaysAttendance;

        // STATE 1: User hasn't clocked in yet today
        if (!$attendance) {
            Attendance::create([
                'agent_id'   => $user->id,
                'shift_date' => Carbon::today()->toDateString(),
                'clock_in'   => Carbon::now(),
            ]);
            return back()->with('success', 'You have successfully clocked in! Have a great shift.');
        }

        // STATE 2: User is clocked in and now needs to clock out
        if ($attendance->clock_in && !$attendance->clock_out) {
            $clockInTime = Carbon::parse($attendance->clock_in);
            $clockOutTime = Carbon::now();

            // 1. Calculate total minutes worked
            $totalSeconds = $clockInTime->diffInSeconds($clockOutTime);
            $work_minutes = number_format(($totalSeconds / 60), 2);

            // 2. AUTOMATIC TASK PAUSE LOGIC: 
            $this->pauseActiveTasks($user->id, $clockOutTime);

            // 3. Process the clock out
            $attendance->update([
                'clock_out'      => $clockOutTime,
                'work_minutes' => $work_minutes,
            ]);
            return back()->with('success', 'You have successfully clocked out!.');
        }

        // STATE 3: Guard condition if they try to double-click a finished shift
        return back()->with('error', 'Your shift for today has already been completed.');
    }

    /**
     * Automatically set all active tasks and assignments to 'On Hold' and log pauses.
     */
    public function pauseActiveTasks($userId, $clockOutTime)
    {
        // 1. Pause regular Tasks
        $activeTasks = Task::where('agent_id', $userId)
            ->where('status', 'In Progress')
            ->get();

        foreach ($activeTasks as $task) {
            $task->update(['status' => 'On Hold']);

            TaskPause::create([
                'task_id'    => $task->id,
                'start'      => $clockOutTime,
                'end'        => null,
                'created_by' => $userId,
            ]);
        }

        // 2. Pause Assigned Tasks
        $activeTaskAssignments = TaskAssignment::where('agent_id', $userId)
            ->where('status', 'In Progress')
            ->get();

            foreach ($activeTaskAssignments as $taskAssignment) {
                $taskAssignment->update(['status' => 'On Hold']);

                TaskAssignmentPause::create([
                    'task_id'    => $taskAssignment->id, // Note: Verify if this should save to a separate field like assigned_task_id depending on your table architecture
                    'start'      => $clockOutTime,
                    'end'        => null,
                    'created_by' => $userId,
                ]);
            }
    }

    public function removeClockOut(Request $request, $id)
    {
        try {
            // 1. Fetch the targeted user's attendance record for the current active shift
            // It searches using the passed dynamic Employee ID ($id) and targets today's record
            $attendance = Attendance::where('agent_id', $id)
                ->where('shift_date', Carbon::today()->toDateString())
                ->first();

            if (!$attendance) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No shift attendance record found for this employee today.'
                ]);
            }

            // 2. Fail-safe protection check: ensure they actually have a clock out to clear
            if (!$attendance->clock_out) {
                return response()->json([
                    'status'  => 'warning',
                    'message' => 'This employee is already active or hasn\'t clocked out yet.'
                ]);
            }

            // 3. Wipe out the data properties to cleanly revert back to an open shift
            $attendance->update([
                'clock_out'    => null,
                'work_minutes' => 0,
            ]);

            // 4. Successful Response payload execution
            return response()->json([
                'status'  => 'success',
                'message' => 'Clock-out timestamp removed successfully. The employee can now continue working!'
            ]);

        } catch (\Exception $e) {
            // Error safety catch response wrap block
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong on the server: ' . $e->getMessage()
            ]);
        }
    }
}
