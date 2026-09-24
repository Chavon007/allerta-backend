<?php

namespace Modules\User\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
class SignupRequest extends FormRequest {

public function authorize():bool{
    return true;
}

public function rules():array{
    return[
        "full_name" => "required|string|max:255",
        "email" => "required|string|max:255|unique:users,email",
        "username" => "required|string|max:255|unique:users,username",
       "password" => ["required", "string", Password::min(8)->mixedCase()->numbers()->symbols()],
    ];
}
}