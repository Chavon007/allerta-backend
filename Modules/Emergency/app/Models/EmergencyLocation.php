<?php


namespace Modules\Emergency\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class EmergencyLocation extends Model{
     protected  $fillable =[
      "emergency_id",
        "address",
        "longitude",
        "latitude"
     ];

     protected function casts():array
     {
      return [
         "latitude" => "float",
         "longitude" => "float",
      ];
     }

     public function emergency():BelongsTo{
      return $this->belongsTo(Emergency::class);
     }
}