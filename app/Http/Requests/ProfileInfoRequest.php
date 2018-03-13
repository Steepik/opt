<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileInfoRequest extends FormRequest
{
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
     * @return array
     */
    public function rules()
    {
        return [
            'inn' => 'required|min:10|max:12',
        ];
    }

    /**
     * Get messages for our errors
     *
     * @return array
     */
    public function messages()
    {
        return [
            'inn.min' => 'ИНН должен быть более :min цифр',
            'inn.max' => 'ИНН должен быть не более :max цифр'
        ];
    }
}
