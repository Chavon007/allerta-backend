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

    public int $tries = 3;
    public int $backoff = 5;
    /**
     * Create a new job instance.
     */
    public function __construct( public int $emergencyId) {}

    /**
     * Execute the job.
     */
    public function handle(EmergencyLocationRepositoryInterface $repository): void {
        $emergency = $repository->findById($this->emergencyId);

        if(!$emergency || $emergency->status !== "active"){
            return;
        }

        $response = Http::withHeaders([
            // Nominatim identifying User-Agent

            "User-Agent" => "Allerta/1.0 (" . env("NOMINATIM_CONTACT_EMAIL") . ")",
        ])->timeout(3)->get("https://nominatim.openstreetmap.org/reverse", [
            "format" => "jsonv2",
            "lat" => $emergency->latest_latitude,
            "lon" => $emergency->latest_longitude,
        ]);


        if($response->failed()){
            Log::warning("Reverse geocode failed", ["emergency" => $this->emergencyId]);
            $this->release($this->backoff);

            return;
        }

        $address = $response->json("display_name");


        if($address){
            $repository->updateAddress($this->emergencyId, $address);
        }

        
    }
}
