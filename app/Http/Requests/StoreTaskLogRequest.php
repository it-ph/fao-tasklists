<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskLogRequest extends FormRequest
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
            'task_id' => ['required'],
            'activity' => ['required'],
            'created_by' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'task_id.required' => 'Task Number is required.',
            'activity.required' => 'Activity is required.',
            'created_by.required' => 'Created by is required.',
        ];
    }
}
