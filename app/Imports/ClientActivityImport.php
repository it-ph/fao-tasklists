<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Permission;
use App\Models\ClientActivity;
use Illuminate\Support\Collection;
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
        $user = User::where('email', $row['email_address'])->select('id')->first();

        $this->row_number += 1;
        if($user)
        {
            $user_id = $user->id;
        }
        else
        {
            $ctr_error += 1;
            array_push($this->has_error, " Check Cell B".$this->row_number.", "."Email Address: ".$row['email_address']." does not exist.");
        }

        $client_activity = $row['client_activity'];

        if($ctr_error <= 0)
        {
            ClientActivity::updateOrCreate(
                [
                    'agent_id' => $user_id,
                    'name' => $client_activity
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
            '*.client_activity' => ['required']
        ];
    }

}
