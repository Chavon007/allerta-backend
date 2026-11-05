<?php


namespace Modules\Emergency\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmergencyLocationRequest extends FormRequest{
    public function authorize():bool{
        return true;
    }

    public function rules():array{
        return[

            "latitude" => ["required", "numeric", "between:-90,90"],
            "longitude" => ["require", "numeric", "between:-180,180"],
        ];
    }
}