<?php

namespace App\Http\Requests\ApiV1;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class LoginRequest extends FormRequest
{

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
        return  [
            'email' => 'required|exists:users,email',
            'password' => 'required'

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Set Header Error
        $containsApiv1 = Str::of($this->route()->getPrefix())->contains('apiv1');

        if ($containsApiv1) {
            request()->headers->set('Accept', 'application/json');
        }

        parent::failedValidation($validator);
    }
}
