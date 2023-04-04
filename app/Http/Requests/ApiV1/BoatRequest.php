<?php

namespace App\Http\Requests\ApiV1;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class BoatRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $exceptId = $this->boat ?? null;
        return  [
            'code'            => "required|min:5|unique:user_boats,code,{$exceptId}",
            'name'            => 'required|min:5',
            'owner'           => 'required|min:5',
            'address'         => 'required|min:5',
            'size'            => 'required|numeric|min:5',
            'captain_name'    => 'required|min:5',
            'total_abk'       => 'required|numeric|min:1',
            'document_number' => 'required_with:document',
            'document'        => 'nullable|mimes:pdf|max:512',
            'image'           => 'nullable|mimes:jpg,png|max:512',
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
