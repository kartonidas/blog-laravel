<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        if(in_array($this->user()->user_role, ['admin']))
            return true;
    
        return false;
    }

    public function rules(): array
    {
        if(!empty($this->id))
        {
            $rule = [
                // Nazwa użytkownika
                'name' => 'sometimes|required|min:3,max:15',
                // Adres e-mail
                'email' => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($this->id, 'id')],
                // Hasło
                'password' => ['sometimes', 'required'],
                // Rola
                'user_role' => ['sometimes', 'required', Rule::in(User::getAllowedRoles())],
            ];
        }
        else
        {
            $rule = [
                // Nazwa użytkownika
                'name' => 'required|min:3,max:15',
                // Adres e-mail
                'email' => 'required|email|unique:users,email',
                // Hasło
                'password' => ['required'],
                // Rola
                'user_role' => ['required', Rule::in(User::getAllowedRoles())],
            ];
            
        }
        
        $rule['password'][] = Password::min(8)->letters()->mixedCase()->numbers()->symbols();
        
        return $rule;
    }
}
