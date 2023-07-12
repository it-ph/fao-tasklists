<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTasksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cluster_id' => ['required'],
            'client_id' => ['required'],
            'agent_id' => ['required'],
            'accounting_period' => ['required'],
            'dashboard_activity_id' => ['required'],
            'client_activity_id' => ['required'],
            'client_detailed_activity' => ['required'],
            'poc' => ['required'],
            'frequency' => ['required'],
            'due_date' => ['required'],
            'estimated_handling_time' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'cluster_id.required' => 'Cluster Name is required.',
            'client_id.required' => 'Client Name is required.',
            'agent_id.required' => 'Employee Name is required.',
            'accounting_period.required' => 'Accounting Period is required.',
            'dashboard_activity_id.required' => 'Accounting Period is required.',
            'client_activity_id.required' => 'Client Activity Name is required.',
            'client_detailed_activity.required' => 'Client Detailed Activities is required.',
            'poc.required' => 'POC is required.',
            'frequency.required' => 'Frequency is required.',
            'due_date.required' => 'Due Date is required.',
            'estimated_handling_time.required' => 'Estimated Handling Time is required.',
        ];
    }
}
