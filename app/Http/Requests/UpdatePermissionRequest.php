<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ResponseTraits;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePermissionRequest extends FormRequest
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
            'user_id' => ['required'],
            'cluster_id' => ['required'],
            'permission' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Employee Name is required.',
            'cluster_id.required' => 'Cluster is required.',
            'permission.required' => 'Permission is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->failedValidationResponse($validator->errors());
        throw new HttpResponseException(response()->json($response, 200));
    }
}
