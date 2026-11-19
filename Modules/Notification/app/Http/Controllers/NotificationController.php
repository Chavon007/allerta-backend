<?php

namespace Modules\Notification\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Notification\Http\Requests\DeleteNotificationRequest;
use Modules\Notification\Http\Requests\SaveNotificationRequest;
use Modules\Notification\Services\DeviceTokenService;

class NotificationController extends Controller
{
    public function __construct(protected DeviceTokenService $deviceToken)
    {}


    public function saveDeviceToken(SaveNotificationRequest $saveRequest){
         $saveDevice = $this->deviceToken->registerService(
        $saveRequest->user()->id,
        $saveRequest->validated("token"),
        $saveRequest->validated("platform")
    );
        return response()->json($saveDevice, 201);
    }
    public function deleteDeviceToken(DeleteNotificationRequest $deleteRequest){
        $deleteDevice = $this->deviceToken->removeService($deleteRequest->user()->id, $deleteRequest->validated("token"));
        return response()->json($deleteDevice, 200);
    }

    public function getTokenController(Request $request){
        $getToken = $this->deviceToken->getTokensService($request->user()->id);


        return response()->json($getToken, 200);
    }
}
