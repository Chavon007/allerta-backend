<?php

namespace Modules\Emergency\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Modules\Emergency\Interfaces\EmergencyLocationRepositoryInterface;
use Modules\Notification\Models\DeviceToken;
use Modules\Notification\Services\PushNotificationService;

class NotifyContacts implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public int $emergencyId) {}

    public function handle(
        EmergencyLocationRepositoryInterface $repository,
        PushNotificationService $pushService
    ): void {
        $emergency = $repository->findById($this->emergencyId);

        if (!$emergency || $emergency->status !== "active") {
            return;
        }

        $user = $emergency->user;
        $contactIds = $user->emergencyContacts->pluck("id")->all();

        $repository->createRecipients($emergency->id, $contactIds);

        $tokens = DeviceToken::whereIn("user_id", $contactIds)
            ->pluck("token")
            ->all();

        $location = $emergency->latest_address
            ?? "Live location available, open the app to track";

        $pushService->sendEmergencyNotification(
            $tokens,
            $user->full_name,
            $location,
            $emergency->id,
            $emergency->latest_latitude,
            $emergency->latest_longitude
        );
    }
}