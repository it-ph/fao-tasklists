<?php

namespace App\Http\Requests;

use App\Traits\ResponseTraits;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskAssignmentRequest extends FormRequest
{
    use ResponseTraits;
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
            'schedule' => ['required'],
            'applicable_month' => ['required'],
            'activity_name' => ['required'],
            'eclerx_function' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'schedule.required' => 'Schedule is required.',
            'applicable_month.required' => 'Applicable Month is required.',
            'activity_name.required' => 'Activity Name is required.',
            'eclerx_function.required' => 'Eclerx Function is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->failedValidationResponse($validator->errors());
        throw new HttpResponseException(response()->json($response, 200));
    }
}
