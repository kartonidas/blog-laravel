<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostDestroyRequest extends FormRequest
{
    public function authorize(): bool
    {
        if(in_array($this->user()->user_role, ['editor', 'admin']))
            return true;
    
        return false;
    }
}
