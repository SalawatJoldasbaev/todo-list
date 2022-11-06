<?php

namespace App\Http\Requests;

use App\Src\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'phone' => 'required|unique:users,phone',
            'name' => 'required',
            'password' => 'required',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Response::error($validator->errors()->first(), 422));
    }
}
