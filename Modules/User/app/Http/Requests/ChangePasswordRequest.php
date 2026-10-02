<?php

namespace Modules\User\Http\Requests;

use illuminate\Foundation\Http\FormRequest;
use Override;

class ChangePasswordRequest extends  FormRequest{
    public function authorize():bool{
        return true;
    }

    public function rules():array{
        return [
            'current_password'     => ['required', 'current_password:sanctum'],
            'new_password'         => ['required', 'string', Password::min(8), 'different:current_password'],
            'confirm_new_password' => ['required', 'same:new_password'],
        ];
    }

  
    public function messages():array
    {
        return [
            'current_password.current_password' => 'Your current password is incorrect.',
            'new_password.different'            => 'New password must be different from your current password.',
        ];
    }


}