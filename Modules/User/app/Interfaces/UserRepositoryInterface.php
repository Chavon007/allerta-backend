<?php

namespace Modules\User\Interfaces;

interface UserRepositoryInterface{
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id):bool;
    public function find(int $id);
    public function findByEmail(string $email);
}