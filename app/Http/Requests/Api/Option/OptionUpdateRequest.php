<?php

namespace App\Http\Requests\Api\Option;

use Illuminate\Foundation\Http\FormRequest;


class OptionUpdateRequest extends FormRequest {

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

            'abbr' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
        ];
    }
}
