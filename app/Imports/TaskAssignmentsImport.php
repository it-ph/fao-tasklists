<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\TaskAssignment;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class TaskAssignmentsImport implements ToModel, WithHeadingRow,WithValidation,SkipsEmptyRows
{
    private $has_error = array();
    private $row_number = 1; // Assuming row 1 is the header. Data processing begins at row 2.

    public function model(array $row)
    {
        // 1. Advance the tracker immediately for each checked row
        $this->row_number += 1;

        $user = User::where('email', $row['email_address'])->select('id', 'cluster_id', 'tl_id', 'client_id')->first();

        // 2. Guard Clause: Stop and skip if the user does not exist
        if (!$user) {
            array_push($this->has_error, "Check Cell A" . $this->row_number . ", Email Address: " . $row['email_address'] . " does not exist.");
            return null; // Stops processing THIS row and safely jumps to the NEXT row
        }

        $authUser = auth()->user();
        $canUpload = false;

        // TL can upload only if the agent's tl_id matches their own ID
        if ($authUser->permission === 'team leader' && $user->tl_id === $authUser->id) {
            $canUpload = true;
        }
        // OM can upload only if the agent's cluster_id matches their own cluster_id
        elseif ($authUser->permission === 'operations manager' && $user->cluster_id === $authUser->cluster_id) {
            $canUpload = true;
        }
        // ADMIN / SUPERADMIN
        elseif ($authUser->permission === 'admin' || $authUser->permission === 'superadmin') {
            $canUpload = true;
        }

        // 3. Guard Clause: Stop and skip if unauthorized
        if (!$canUpload) {
            array_push($this->has_error, "Check Cell A" . $this->row_number . ", You are not allowed to upload Task Assignment to " . $row['email_address'] . ".");
            return null; // Skip database save for this row and move on
        }

        // 4. Date Formatter Normalization
        try {
            $applicable_month = $this->transformDate($row['applicable_month']);
            $schedule = !empty($row['schedule']) ? $this->transformDate($row['schedule']) : null;
            
            if (!$applicable_month) {
                array_push($this->has_error, "Row " . $this->row_number . ": 'applicable_month' is invalid or improperly formatted.");
                return null;
            }
        } catch (\Exception $e) {
            array_push($this->has_error, "Row " . $this->row_number . ": Invalid date formatting found. Please confirm 'applicable_month' and 'schedule' use a valid date format.");
            return null;
        }

        // 5. Safe Insertion (Only runs if all checks above passed)
        return TaskAssignment::updateOrCreate(
            [
                'agent_id'           => $user->id,
                'cluster_id'         => $user->cluster_id,
                'activity_name'      => $row['activity_name'],
                'applicable_month'   => $applicable_month,
                'eclerx_function'    => $row['eclerx_function'],
            ],
            [
                'client_id'          => $user->client_id ?? 0,
                'client_function'    => $row['client_function'] ?? null,
                'schedule'           => $schedule,
                'created_by'         => Auth::id(),
                'status'             => 'Not Started',
                'quality'            => 'Green'
            ]
        );
    }

    public function getErrors()
    {
        return $this->has_error;
    }

    public function rules(): array
    {
        // Removed *. to map directly to headings processed line-by-line
        return [
            'email_address'    => ['required'],
            'activity_name'    => ['required'],
            'applicable_month' => ['required'],
            'eclerx_function'  => ['required'],
            'schedule'         => ['required'],
        ];
    }

    private function transformDate($value)
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);

        if (is_numeric($value)) {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
        }

        try {
            return Carbon::createFromFormat('M-y', $value)->startOfMonth()->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return Carbon::createFromFormat('m-Y', $value)->startOfMonth()->format('Y-m-d');
            } catch (\Exception $ex) {
                return Carbon::parse($value)->startOfMonth()->format('Y-m-d');
            }
        }
    }
}
