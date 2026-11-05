<?php

namespace Modules\Emergency\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Emergency\Http\Requests\EmergencyLocationRequest;
use Modules\Emergency\Services\EmergencyLocationService;

class EmergencyController extends Controller
{


public function __construct(protected EmergencyLocationService $emergencyLocation)
{}
 

public function startEmergency(EmergencyLocationRequest $request){
    $emergency = $this->emergencyLocation->startEmergency($request->user()->id, $request->validated("latitude"), $request->validated("longitude"));

    return response()->json($emergency, 201);
}

public function addLocation(EmergencyLocationRequest $request,int $id){
    $location = $this->emergencyLocation->addLocationService($request->user()->id, $id, $request->validated("latitude"), $request->validated("longitude"));

    return response()->json($location);
}

public function endEmergency(Request $request, int $id):JsonResponse{
    $emergency = $this->emergencyLocation->endEmergencyService($request->user()->id, $id);

    return response()->json($emergency);
}

public function show(int $id):JsonResponse{
    return response()->json($this->emergencyLocation->findById($id));
}


public function history(Request $request, int $id):JsonResponse{
    return response()->json($this->emergencyLocation->getHistoryService($request->user()->id, $id));
}
}
