<?php

namespace App\Http\Requests\ApiV2;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AuthorRegistrationRequest extends FormRequest
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
            'name'                  => 'required|min:3',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|same:password_confirmation',
            'password_confirmation' => 'required'
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
