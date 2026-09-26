<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\EmergencyContactRequest;
use Modules\User\Services\EmergencyContactService;

class EmergencyContactController extends Controller
{
    public function __construct(protected EmergencyContactService $emergencyContactService)
    {}

  public function addContact(EmergencyContactRequest $request)
{
    $contacts = $this->emergencyContactService->addContact(
        $request->user()->id,
        $request->validated('identifier')
    );

    return response()->json($contacts);
}
}