<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Permission;
use App\Models\ClientActivity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ClientActivityImport implements ToModel, WithHeadingRow,WithValidation,SkipsEmptyRows
{
    private $has_error = array();
    private $row_number = 1;
    public function model(array $row)
    {
        $ctr_error = 0;
        array_push($this->has_error, "Something went wrong, Please check all entries that you have encoded.");
        $user = User::where('email', $row['email_address'])->select('emp_id')->first();

        // check if haspermission

        $this->row_number += 1;
        if($user)
        {
            $user_id = $user->emp_id;
        }
        else
        {
            $ctr_error += 1;
            array_push($this->has_error, " Check Cell B".$this->row_number.", "."Email Address: ".$row['email_address']." does not exist.");
        }

        // check if isAccountant() - must only upload own client activities
        $is_accountant_with_invalid_email = Auth::user()->isAccountant() && Auth::user()->email <> $row['email_address'] ? true : false;
        if($is_accountant_with_invalid_email)
        {
            $ctr_error += 1;
            array_push($this->has_error, " Check Cell B".$this->row_number.", "."Email Address: ".$row['email_address']." does not belong to you.");
        }

        if($ctr_error <= 0)
        {
            ClientActivity::updateOrCreate(
                [
                    'agent_id' => $user_id,
                    'name' => $row['activity']
                ],
                [
                    'frequency' => strtolower($row['frequency']),
                    'schedule' => $row['schedule'],
                    'function' => $row['function'],
                ]
            );
        }
    }

    public function getErrors()
    {
        return $this->has_error;
    }

    public function rules(): array
    {
        return [
            '*.employee_name' => ['required'],
            '*.email_address' => ['required'],
            '*.activity' => ['required'],
            '*.frequency' => ['required'],
            '*.schedule' => ['required'],
            '*.function' => ['required']
        ];
    }

}
