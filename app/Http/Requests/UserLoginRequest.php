<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Adres e-mail
            'email' => 'required|email',
            // Hasło
            'password' => 'required',
        ];
    }
}
