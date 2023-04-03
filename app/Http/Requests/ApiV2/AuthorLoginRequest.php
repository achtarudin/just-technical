<?php

namespace App\Http\Requests\ApiV2;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AuthorLoginRequest extends FormRequest
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
            'email'                 => 'required|email|exists:users,email',
            'password'              => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Set Header Error
        $containsApiv2 = Str::of($this->route()->getPrefix())->contains('apiv2');
        $requestIsJson  = request()->header('Accept') == 'application/json';

        if ($containsApiv2 && $requestIsJson == false) {
            request()->headers->set('Accept', 'application/json');
        }

        parent::failedValidation($validator);
    }
}
