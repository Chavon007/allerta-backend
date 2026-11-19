<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Models\User
// use Modules\Notification\Database\Factories\DeviceTokenFactory;

class DeviceToken extends Model
{
    use HasFactory;

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
        return $this->hasMany(Iser::class);
    }
}
