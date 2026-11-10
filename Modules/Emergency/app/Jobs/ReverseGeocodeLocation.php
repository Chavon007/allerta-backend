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
    $response = Http::timeout(10)->get("https://api.geoapify.com/v1/geocode/reverse", [
        "lat" => $emergency->latest_latitude,
        "lon" => $emergency->latest_longitude,
        "format" => "json",
        "apiKey" => config("services.geoapify.key"),
    ]);

    if ($response->failed()) {
        Log::warning("Geoapify error", [
            "status" => $response->status(),
            "body" => $response->body(),
        ]);
        return;
    }

    $result = $response->json("results.0");

    if ($result) {
        // for a shop or business, skip its name and keep only the street part
        $address = ($result["result_type"] ?? null) === "amenity"
            ? ($result["address_line2"] ?? $result["formatted"] ?? null)
            : ($result["formatted"] ?? null);

        if ($address) {
            $repository->updateAddress($this->emergencyId, $address);
        }
    }
} catch (\Throwable $e) {
    // never break the chain, NotifyContacts must still run
    Log::warning("Geocode failed: " . $e->getMessage());
}
    }
}