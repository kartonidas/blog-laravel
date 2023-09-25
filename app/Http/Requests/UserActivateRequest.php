<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserActivateRequest extends FormRequest
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
            // Token aktywacyjny (z wiadomości e-mail)
            'token' => 'required',
        ];
    }
}
