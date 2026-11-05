<?php

namespace Modules\Emergency\Services;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;
use Modules\Emergency\Interfaces\EmergencyLocationRepositoryInterface;
use Modules\User\Interfaces\UserRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Modules\Emergency\Jobs\NotifyContacts;
use Modules\Emergency\Jobs\ReverseGeocodeLocation;
use Modules\Emergency\Models\Emergency;
use Modules\Emergency\Models\EmergencyLocation;

class EmergencyLocationService{
    public function __construct(protected EmergencyLocationRepositoryInterface $emergencyRepository)
    {}


    public function startEmergency(int $userId, float $latitude, float $longitude):Emergency{

        if($this->emergencyRepository->findActiveByUser($userId)){
            throw ValidationException::withMessages([ "emergency" => "You already have an active emergency",]);
        }

        $emergency = $this->emergencyRepository->createEmergency($userId, $latitude, $longitude);

           Bus::chain([
            new ReverseGeocodeLocation($emergency->id),
           new NotifyContacts($emergency->id),
           ])->dispatch();
        return $emergency;
    }
    
    public function addLocationService(int $userId, int $emergencyId, float $latitude, float $longitude):EmergencyLocation{

        $emergency = $this->getHistoryService($userId, $emergencyId);


        $lastGeocoded = $emergency->location()->whereNotNull("address")->latest("id")->first();

        $location = $this->emergencyRepository->addLocation($emergency->id, $latitude, $longitude);

        if(!$lastGeocoded || $this->distanceInMeters(
            $lastGeocoded->latitude, $lastGeocoded->longitude, $latitude, $longitude
        )> 50){
            ReverseGeocodeLocation::dispatch($emergency->id);
        }
        return $location;
    }

    public function endEmergencyService(int $userId, int $emergencyId):Emergency{
        $emergency = $this->getOwnedActiveEmergency($userId, $emergencyId);

        return  $this->emergencyRepository->endEmergency($emergency->id);
    }

    public function findActiveByUserService(int $userId):?Emergency{
        $emergency = $this->emergencyRepository->findActiveByUser($userId);
        return $emergency;
    }

    public function findById(int $emergencyId):Emergency{
        $emergency = $this->emergencyRepository->findById($emergencyId);

      abort_if(!$emergency, 404, "Emergency not found");
        return $emergency;
    }

    public function getHistoryService(int $userId, int $emergencyId){
        $emergency = $this->findById($emergencyId);



       abort_if($emergency->user_id !== $userId, 403, "Not allowed");
        return $this->emergencyRepository->getHistory($emergencyId);
    }


    public function updateAddressService(int $emergencyId, string $address):void{
        $this->emergencyRepository->updateAddress($emergencyId, $address);
    }

    public function getOwnedActiveEmergency(int $userId, int $emergencyId):Emergency{
        $emergency = $this->findById($emergencyId);

        abort_if($emergency->user_id !== $userId, 403, "Not allowed");
        abort_if($emergency->status !== "active", 422, "This emergency has ended");

        return $emergency;
    }



    public function distanceInMeters(float $lat1, float $lon1, float $lat2, float $lon2):float{
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) **2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2 ) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}