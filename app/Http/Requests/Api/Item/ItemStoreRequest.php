<?php

namespace App\Http\Requests\Api\Item;

use App\Enums\ESoftStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class ItemStoreRequest extends FormRequest {

    public function prepareForValidation(): void
    {
        if (is_null($this->status)) {
            $this->merge(['status' => ESoftStatus::ACTIVE->value]);
        }

        if (!is_null($this->options)) {
            $this->merge(['options' => explode(',', $this->options)]);
        }

        if (!is_null($this->images)) {
            $this->merge(['images' => explode(',', $this->images)]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [

            'title' => ['required', 'string', 'min:3', 'max:128'],
            'body' => ['nullable', 'string', 'max:1000'],
            'stock' => ['nullable', 'integer'],
            'price' => ['required', 'decimal:0,2'],
            'status' => ['nullable', 'integer', new Enum(ESoftStatus::class)],
            'options' => ['nullable'],
            'options.*' => ['nullable', 'integer', 'exists:options,id'],
            'images' => ['nullable'],
            'images.*' => ['nullable', 'integer', 'exists:images,id'],
        ];
    }
}
