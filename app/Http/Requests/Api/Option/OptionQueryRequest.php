<?php

namespace App\Http\Requests\Api\Option;

use App\Enums\EOrderReverse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class OptionQueryRequest extends FormRequest {

    public function prepareForValidation()
    {
        if (is_null($this->order)) {
            $this->merge(['order' => 'title']);
        }

        if (is_null($this->reverse)) {
            $this->merge(['reverse' => EOrderReverse::ASC->value]);
        }
    }

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

            'q' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'order' => ['nullable', 'string', 'min:1', 'max:100'],
            'reverse' => ['nullable', new Enum(EOrderReverse::class)],
        ];
    }
}
