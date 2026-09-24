<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Interfaces\UserRepositoryInterface;

class AuthService{
    public function __construct( protected UserRepositoryInterface $userRepository)
    {}

    public function login(array $credentials){
        $user = $this->userRepository->findByEmail($credentials["email"]);

        if(!$user || !Hash::check($credentials["password"], $user->password)){
            throw ValidationException::withMessages([ 'email' => ['The provided credentials are incorrect.'],]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

     return [
            'user' => $user,
            'token' => $token,
        ];
    }
     

}