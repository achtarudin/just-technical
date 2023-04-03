<?php

namespace App\Http\Requests\ApiV2;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AuthorCreateArticleRequest extends FormRequest
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
        $exceptId = $this->article ?? null;
        return  [
            'image'                 => 'nullable|mimes:png,jpg|max:512',
            'title'                 => "required|min:10|unique:articles,title,{$exceptId}",
            'content'               => 'required|min:10',
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
