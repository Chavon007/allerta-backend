<?php

namespace Modules\Notification\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Modules\Notification\Models\DeviceToken;
interface DeviceTokenRepositoryInterface {
    public function saveToken(int $userId, string $token, string $platform): DeviceToken;
    public function deleteToken(int $userId, string $token): bool;
    public function getTokenByUser(int $userId): Collection;
    public function deleteTokens(array $token): int;
}
