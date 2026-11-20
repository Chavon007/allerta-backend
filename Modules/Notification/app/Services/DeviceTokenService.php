<?php

namespace Modules\Notification\Services;
use Modules\Notification\Interfaces\DeviceTokenRepositoryInterface;
use Modules\Notification\Models\DeviceToken;
use Illuminate\Database\Eloquent\Collection;

class DeviceTokenService
{

    public function __construct(protected DeviceTokenRepositoryInterface $deviceTokenRepository)
    {}
    public function registerService(int $userId, string $token, string $platform): DeviceToken{
        $device = $this->deviceTokenRepository->saveToken($userId, $token, $platform);

        return $device;
    }

    public function removeService(int $userId, string $token): bool{
        $device = $this->deviceTokenRepository->deleteToken($userId, $token);

        return $device;
    }

    public function getTokensService(int $userId):Collection{
        $device = $this->deviceTokenRepository->getTokenByUser($userId);
        return $device;
    }
}
