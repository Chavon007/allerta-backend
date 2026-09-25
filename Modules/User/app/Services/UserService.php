<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Hash;
use Modules\User\Interfaces\UserRepositoryInterface;



class UserService {
    
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }


    // create account

    public function signup(array $data){
        $data["password"] = Hash::make($data["password"]);

        return $this->userRepository->create($data);
    }

    // update user

    public function updateUser(int $id, array $data){
        return $this->userRepository->update($id, $data);
    }

    public function deletUser(int $id){
        return $this->userRepository->delete($id);
    }

    public function findUser(int $id){
        return $this->userRepository->find($id);
    }

} 