<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserDestroyRequest extends FormRequest
{
    public function authorize(): bool
    {
        if(in_array($this->user()->user_role, ['admin']))
            return true;
    
        return false;
    }
}
