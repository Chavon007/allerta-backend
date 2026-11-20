<?php


namespace Modules\Notification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class DeleteNotificationRequest extends FormRequest{
    public function authorize():bool{
        return true;
    }

    public function rules():array{
        return[
            "token" => ["required", "string"]
        ];
    }
}