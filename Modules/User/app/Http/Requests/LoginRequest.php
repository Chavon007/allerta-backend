<?php

namespace Modules\User\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest{
    public function authorize():bool{
        return true;
    }

    public function rules():array{
        return[
            "email" => "required|string|email",
            "password" => "required|string",
        ];
    }

    protected function prepareForValidation(): void{
        if($this->has("email")){
            $this->merge(["email" => Str::lower(trim($this->email))]);
        }
    }
}