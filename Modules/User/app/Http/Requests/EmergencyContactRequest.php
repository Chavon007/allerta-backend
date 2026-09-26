<?php
namespace Modules\User\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class EmergencyContactRequest extends FormRequest{
    public function authorize():bool{
        return true;
    }

    public function rules():array{
      return [
            "identifier" => "required|string",
        ];
    }
}