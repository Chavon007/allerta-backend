<?php
namespace Modules\Emergency\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Modules\Emergency\Models\Emergency;
use Modules\Emergency\Models\EmergencyLocation;

interface EmergencyLocationRepositoryInterface{
    public function createEmergency(int $userId, float $latitude, float $longitude):Emergency;
    public function addLocation(int $emergencyId, float $latitude, float $longitude, ?string $address = null):EmergencyLocation;
    public function endEmergency(int $emergencyId):?Emergency;
    public function findActiveByUser(int $userId):?Emergency;
    public function findById(int $emergencyId):?Emergency;
    public function getHistory(int $emergencyId):Collection;
    public function updateAddress(int $emergencyId, string $address):void;
}