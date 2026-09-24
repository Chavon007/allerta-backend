<?php

namespace Modules\User\Repositories;

use Modules\User\Models\User;
use Modules\User\Interfaces\UserRepositoryInterface;


class UserRepository implements UserRepositoryInterface{
    
    
    public function __construct(protected User $user){
    }

    
    public function create(array $data)
    {
       return $this->user::create($data);
    }

    
    public function update(int $id, array $data)
    {
        $user = $this->user::findOrFail($id);
        $user->update($data);
        return $user;
    }

    
    public function delete(int $id): bool
    {
       return (bool) $this->user::where("id", $id)->delete();
    }

    public function find(int $id)
    {
        return $this->user::find($id);
    }

  
    public function findByEmail(string $email): ?User
    {
        return $this->user::where("email", $email)->first();
    } 
}