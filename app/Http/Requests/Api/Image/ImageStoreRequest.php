<?php

namespace App\Http\Requests\Api\Image;

use Illuminate\Foundation\Http\FormRequest;


class ImageStoreRequest extends FormRequest {

    public function messages()
    {
        return [
            'mimes' => 'Формат выбранного файла не поддерживается. Доступные форматы: :values.',
            'max.file' => 'Слишком большой размер файла. Доступны файлы до 25 Мб.',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'path' => ['required', 'string'],
            'file' => [
                'required',
                'file',
                'max:25000',
                'mimes:jpeg,jpg,png,webp,gif'
            ],
        ];
    }
}
