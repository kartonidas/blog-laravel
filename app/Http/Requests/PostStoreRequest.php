<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        if(in_array($this->user()->user_role, ['editor', 'admin']))
            return true;
    
        return false;
    }

    public function rules(): array
    {
        $required = 'required';
        if(!empty($this->id))
            $required = 'sometimes|required';
        
        return [
            // Tytuł
            'title' => $required . '|min:5|max:150',
            
            // Treść
            'content' => $required . '|max:5000',
            
            // Zdjęcia
            'images.*' => ['nullable', 'image'],
        ];
    }
}
