<?php

namespace Modules\Emergency\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Emergency\Interfaces\EmergencyLocationRepositoryInterface;

class ReverseGeocodeLocation implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $emergencyId) {}

    public function handle(EmergencyLocationRepositoryInterface $repository): void
    {
        $emergency = $repository->findById($this->emergencyId);

        if (!$emergency || $emergency->status !== "active") {
            return;
        }

        try {
            $response = Http::withHeaders([
                    "User-Agent" => "Allerta/1.0 (" . config("services.nominatim.contact_email") . ")",
                ])
                ->timeout(10)
                ->get("https://nominatim.openstreetmap.org/reverse", [
                    "format" => "jsonv2",
                    "lat" => $emergency->latest_latitude,
                    "lon" => $emergency->latest_longitude,
                ]);

            if ($response->failed()) {
                Log::warning("Geocode HTTP error", [
                    "status" => $response->status(),
                    "body" => $response->body(),
                ]);
                return;
            }

            $address = $response->json("display_name");

            if ($address) {
                $repository->updateAddress($this->emergencyId, $address);
            }
        } catch (\Throwable $e) {
            // never break the chain: NotifyContacts must still run
            Log::warning("Geocode failed: " . $e->getMessage());
        }
    }
}