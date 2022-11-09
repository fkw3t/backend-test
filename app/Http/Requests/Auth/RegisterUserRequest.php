<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Rules\CpfOrCnpj;
use App\Rules\PersonType;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

final class RegisterUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'person_type' => ['required', 'string', Rule::in(['fisical', 'legal'])],
            'document_id' => [
                'required',
                'unique:users',
                'unique:sellers',
                'numeric',
                new CpfOrCnpj,
                new PersonType($this->request->get('person_type'))
            ],
            'email' => [
                'required',
                'unique:users',
                'unique:sellers',
                'email'
            ],
            'password' => ['required', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->all()], 422);
        throw new ValidationException($validator, $response);
    }
}
