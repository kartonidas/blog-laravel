<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        if(in_array($this->user()->user_role, ['admin']))
            return true;
    
        return false;
    }

    public function rules(): array
    {
        return [
            // Ilość zwracanych rekordów (domyślnie 10)
            "size" => "nullable|integer|gt:0",
            // Aktualna strona
            "page" => "nullable|integer|gt:0",
        ];
    }
}
