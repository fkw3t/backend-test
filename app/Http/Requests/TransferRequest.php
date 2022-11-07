<?php

namespace App\Http\Requests;

use App\Rules\RegisteredProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        # todo: add policy
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'payer_id' => ['required', 'uuid', new RegisteredProvider],
            'payee_id' => ['required', 'uuid', new RegisteredProvider],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['string', 'max:256']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->all()], 422);
        throw new ValidationException($validator, $response);
    }
}
