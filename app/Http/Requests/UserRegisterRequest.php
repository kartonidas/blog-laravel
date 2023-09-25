<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Nazwa użytkownika
            'name' => 'required|min:3|max:15',
            // Adres e-mail
            'email' => 'required|email|unique:users,email',
            // Hasło
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ];
    }
}
