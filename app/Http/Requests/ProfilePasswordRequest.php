<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePasswordRequest extends FormRequest
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
            'old_pass' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'old_pass.min' => 'Старый пароль должен быть не менее :min цифр',
            'password.min' => 'Новый пароль должен быть не менее :min цифр',
            'password.confirmed' => 'Повторный пароль должен совпадать с новым паролем',
            'password_confirmation.min' => 'Повторный пароль должен быть не менее :min цифр',
        ];
    }
}
