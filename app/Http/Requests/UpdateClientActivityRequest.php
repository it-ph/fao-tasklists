<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientActivityRequest extends FormRequest
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
            // 'name' => ['required'],
            'agent_id' => ['required'],
            // 'frequency' => ['required'],
            'schedule' => ['required'],
            // 'function' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            // 'name.required' => 'Activity Name is required.',
            'agent_id.required' => 'Employee Name is required.',
            // 'frequency.required' => 'Frequency is required.',
            'schedule.required' => 'Schedule is required.',
            // 'function.required' => 'Function is required.',
        ];
    }
}
