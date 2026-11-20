<?php

namespace Modules\Notification\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Modules\Notification\Interfaces\DeviceTokenRepositoryInterface;
use Throwable;
class PushNotificationService
{
    public function __construct(protected Messaging $messaging, protected DeviceTokenRepositoryInterface $deviceTokenRepository)
    {}

    public function sendEmergencyNotification(array $token, string $name, string $location, int $emergencyId, ?float $latitude, ?float $longitude):void{

    $this->send($token,  "Emergency Alert",
            "{$name} needs your help. {$location}", [
               "type" => "emergency",
                "emergency_id" => (string) $emergencyId,
                "latitude" => (string) ($latitude ?? ""),
                "longitude" => (string) ($longitude ?? ""), 
            ] );
    }

    public function sendResponseNotification(
        array $token, string $contactName, int $emergencyId
    ): void{
        $this->send($token, "Help is on the way", "{$contactName} is on the way to you.",
            [
                "type" => "response",
                "emergency_id" => (string) $emergencyId,
            ]);
    }


    protected function send(array $token, string $title, string $body, array $data): void{
        if(empty($token)){
            return;
        }

        try{
            $message = CloudMessage::new()->withNotification(Notification::create($title, $body))->withData($data)->withAndroidConfig([
                "priority" => "high",
                    "notification" => [
                        "channel_id" => "emergency",
                        "sound" => "default",
                    ],
            ]);

            $report = $this->messaging->sendMulticast($message, $token );

            $invalid = array_merge(
                $report->invalidTokens(),
                $report->unknownTokens()
            );

            if(!empty($invalid)){
                $this->deviceTokenRepository->deleteTokens($invalid);
            }
        }catch(Throwable $e){
            Log::error("push failed: " . $e->getMessage());
        }
    }
}
