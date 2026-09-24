<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function show($id)
    {
        $user = $this->userService->findUser($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = $this->userService->updateUser($id, $request->all());
        return response()->json($user);
    }

    public function destroy($id)
    {
        $this->userService->deletUser($id);
        return response()->json(['message' => 'User deleted successfully']);
    }
}