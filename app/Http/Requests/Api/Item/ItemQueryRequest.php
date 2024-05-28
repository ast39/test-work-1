<?php

namespace App\Http\Requests\Api\Item;

use App\Enums\EOrderReverse;
use App\Enums\ESoftStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class ItemQueryRequest extends FormRequest {

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

            '*' => ['sometimes'],
            'q' => ['nullable', 'string'],
            'status' => ['nullable', new Enum(ESoftStatus::class)],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'order' => ['nullable', 'string', 'min:1', 'max:100'],
            'reverse' => ['nullable', new Enum(EOrderReverse::class)],
        ];
    }

    // Переопределяем метод validationData для обработки значений параметров
    public function validationData()
    {
        $data = $this->all();

        foreach ($data as $key => $value) {
            if (is_string($value) && stripos($key, 'option_') === 0) {
                $data['options'][str_ireplace('option_', '', $key)] = explode(',', $value);
                unset($data[$key]);
            }
        }

        return $data;
    }
}
