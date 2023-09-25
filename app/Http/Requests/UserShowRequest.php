<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        if(in_array($this->user()->user_role, ['admin']))
            return true;
    
        return false;
    }
}
