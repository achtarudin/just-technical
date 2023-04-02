<?php

namespace App\Http\Requests\ApiV1;

use App\Models\Otp\OtpRegistrationModel;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AdminVerifiedUserRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        $pending = OtpRegistrationModel::PENDING;
        $aproved = OtpRegistrationModel::APROVED;
        $rejected = OtpRegistrationModel::REJECTED;

        return  [
            'status' => "required|in:{$pending},{$aproved},{$rejected}",
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

    public function messages()
    {
        $pending = OtpRegistrationModel::PENDING;
        $aproved = OtpRegistrationModel::APROVED;
        $rejected = OtpRegistrationModel::REJECTED;

        return [
            'status.in' => "The :attribute must either {$pending}, {$aproved} or {$rejected}.",
        ];
    }


}
