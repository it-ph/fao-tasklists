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
            'date_received' => ['required'],
            'dashboard_activity_id' => ['required'],
            'client_activity_id' => ['required'],
            'description' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'cluster_id.required' => 'Cluster Name is required.',
            'client_id.required' => 'Client Name is required.',
            'agent_id.required' => 'Employee Name is required.',
            'date_received.required' => 'Date Received is required.',
            'dashboard_activity_id.required' => 'Dashboard Activity is required.',
            'client_activity_id.required' => 'Client Activity is required.',
            'description.required' => 'Description is required.',
        ];
    }
}
