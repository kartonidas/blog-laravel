<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
