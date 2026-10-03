<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Http\Requests\SignupRequest;
use Modules\User\Http\Requests\UpdateProfileRequest;
use Modules\User\Http\Requests\ChangePasswordRequest;
use Modules\User\Services\AuthService;
use Modules\User\Services\UserService;

class AuthController extends Controller{

  public function __construct( protected UserService $userService, protected AuthService $authService)
  {}

  public function signup(SignupRequest $request){
    $user = $this->userService->signup($request->validated());
     
    return response()->json($user);
  }

public function login(LoginRequest $request)
{
    $result = $this->authService->login($request->validated());

    return response()->json([
        'user' => $result['user'],
        'token' => $result['token'],
    ]);
}

public function me(Request $request){
    $user = $this->authService->me($request->user()->id);

    return response()->json($user);
}

public function updateProfile(UpdateProfileRequest $request){
  $user  = $this->userService->updateUser($request->user()->id, $request->validated());

  return response()->json([
      'message' => 'Profile updated successfully',
      'data'    => $user,
  ]);
}
  public function changePassword(ChangePasswordRequest $request){
    $user = $request->user();

    $this->userService->updateUser($user->id, $request->validated()["new_password"]);

    return response()->json(["message" => "Password changed successfully"]);
  }
}