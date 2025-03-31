<?php

namespace App\Http\Requests;

use App\Traits\ResponseTraits;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreClientRequest extends FormRequest
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
            'name' => ['required','unique:clients,name'],
            'cluster_id' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Client Name is required.',
            'name.unique' => 'Client Name already exists.',
            'cluster_id.required' => 'Cluster is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->failedValidationResponse($validator->errors());
        throw new HttpResponseException(response()->json($response, 200));
    }
}
