<?php

namespace Modules\Notification\Repositories;
use Modules\Notification\Interfaces\DeviceTokenRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Modules\Notification\Models\DeviceToken;


class DeviceTokenRepository implements DeviceTokenRepositoryInterface
{
    
    public function saveToken(int $userId, string $token, string $platform): DeviceToken
    {
          $device = DeviceToken::updateOrCreate(["token" => $token], ["user_id" => $userId, "platform" => $platform]);   

          return $device;
    }


    public function deleteToken(int $userId, string $token): bool
    {
        $device = DeviceToken::where(["user_id" => $userId, "token" => $token])->delete();
        
        return $device > 0;
    }

   
    public function getTokenByUser(int $userId): Collection
    {
        $device = DeviceToken::where("user_id", $userId)->get();
        
        return $device;
    }


    public function  deleteTokens(array $token): int
    {
        $device = DeviceToken::whereIn("token", $token)->delete();

        return $device;
    }
}
