<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
   use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest{
    public function authorize():bool{
        return true;
    } 

    public function rules():array{
           $userId = $this->user()->id;
    return [
        'full_name' => ['sometimes', 'required', 'string', 'max:255'],
        'username'  => [
            'sometimes', 'required', 'string', 'min:3', 'max:30',
            'regex:/^[a-zA-Z0-9_]+$/',
            Rule::unique('users', 'username')->ignore($userId),
        ],
        'email'     => [
            'sometimes', 'required', 'email', 'max:255',
            Rule::unique('users', 'email')->ignore($userId),
        ],
    ];
    }
}