<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Http\Requests\SignupRequest;
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
}