<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

public function fetchContacts(Request $request){
 $contacts = $this->emergencyContactService->fetchContacts($request->user()->id);
  return  response()->json($contacts);
}
}