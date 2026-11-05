<?php

namespace Modules\Emergency\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\Models\User;
use Modules\Emergency\Models\EmergencyLocation;


class Emergency extends Model{
 use HasFactory;


 protected $fillable =[
   "user_id",
    "status",
    "latest_latitude",
    "latest_longitude",
    "latest_address",
    "started_at",
    "ended_at"
 ];

 protected function cast():array{
   return [
      "latest_latitude" => "float",
      "latest_longitude" => "float",
      "started_at" => "datetime",
      "ended_at" => "datatime",
   ];
 }


 public function user():BelongsTo{
   return $this->belongsTo(User::class);
 }

 public function location():HasMany{
   return $this->hasMany(EmergencyLocation::class);
 }
}