<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserClientRequest extends FormRequest
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
            'user_id' => ['required'],
            'client_id' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Employee Name is required.',
            'client_id.required' => 'Client Name is required.',
        ];
    }
}
