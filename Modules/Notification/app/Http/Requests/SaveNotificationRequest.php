<?php

namespace Modules\Notification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class SaveNotificationRequest extends FormRequest{
    public function authorize():bool{
        return true;
    }  

    public function rules():array{
        return[
            "token" => ["required", "string"],
            "platform" => ["required", "string", Rule::in(["android", "ios"])]
        ];
    }
}