<?php
namespace Modules\Emergency\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Emergency\Interfaces\EmergencyLocationRepositoryInterface;
use Modules\Emergency\Models\Emergency;
use Modules\Emergency\Models\EmergencyLocation;
use Illuminate\Support\Facades\DB;
use Override;

class EmergencyLocationRepository implements EmergencyLocationRepositoryInterface{
    
    public function createEmergency(int $userId, float $latitude, float $longitude): Emergency
    {
        return DB::transaction(function () use ($userId, $latitude, $longitude){
            $emergency = Emergency::create([
                "user_id" => $userId,
                "status" => "active",
                "latest_latitude" => $latitude,
                "latest_longitude" => $longitude,
                "started_at" => now(),
            ]);

            $emergency->location()->create([
                "latitude" => $latitude,
                "longitude" => $longitude
            ]);

            return $emergency;
        });
    }

    
    public function addLocation(int $emergencyId, float $latitude, float $longitude, ?string $address = null): EmergencyLocation
    {
        return DB::transaction(function () use($emergencyId, $latitude, $longitude, $address ){
            $emergency = Emergency::findOrFail($emergencyId);

            $location = $emergency->location()->create([
                "latitude" => $latitude,
                "longitude" => $longitude,
                "address" => $address,
            ]);

            $emergency->update([
                "latest_latitude" => $latitude,
                "latest_longitude" => $longitude,
                "latest_address" => $address ?? $emergency->latest_address
            ]);
            return $location;
        });
    }

    
    public function endEmergency(int $emergencyId): Emergency
    {
         $emergency = Emergency::findOrFail($emergencyId);

         $emergency->update([
            "status" => "ended",
            "ended_at" => now()
         ]);

         return $emergency;
    }

   
   public function findById(int $emergencyId): ?Emergency
   {
    return Emergency::find($emergencyId);
   }
  
  

    public function findActiveByUser(int $userId): ?Emergency
    {
        return Emergency::where("user_id", $userId)->where("status", "active")->first();
    }

  public function getHistory(int $emergencyId): Collection
  {
    return EmergencyLocation::where("emergency_id", $emergencyId)->orderBy("created_at")->get();
  }

    
    public function updateAddress(int $emergencyId, string $address): void
    {
        $emergency = Emergency::findOrFail($emergencyId);
        $emergency->update(["latest_address" => $address]);

        $emergency->location()->latest()->first()?->update(["address" => $address]);
    }
}