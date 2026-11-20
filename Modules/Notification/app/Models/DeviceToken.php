<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\Models\User;

class DeviceToken extends Model
{
    

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "user_id",
        "token",
        "platform"
    ];

    // protected static function newFactory(): DeviceTokenFactory
    // {
    //     // return DeviceTokenFactory::new();
    // }

    public function deviceTokens():HasMany{
        return $this->hasMany(User::class);
    }
}
