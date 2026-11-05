<?php

namespace Modules\Emergency\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Modules\Emergency\Interfaces\EmergencyLocationRepositoryInterface;

class NotifyContacts implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $emergencyId) {}

    /**
     * Execute the job.
     */
    public function handle(EmergencyLocationRepositoryInterface $repository): void {
        $emergency = $repository->findById($this->emergencyId);

        if(!$emergency || $emergency->status !== "active"){
            return;
        }

        $user = $emergency->user;
        $contacts = $user->emergencyContacts;


                $location = $emergency->latest_address ?? "Live location available, open the app to track";
        foreach($contacts as $contact){
            // TODO: send the real notification (push or SMS) here.
            // Payload: $user->full_name, $emergency->latest_address,
            // $emergency->latest_latitude, $emergency->latest_longitude.

            Log::info("Notify {$contact->full_name} about emergency {$emergency->id}");
        }
    }
}
