<?php

namespace Modules\User\Interfaces;

interface EmergencyContactRepositoryInterface{
    public function search(int $userId, string $query);
    public function addContact(int $userId, int $contactUserId);
    public function removeContactList(int $userId, int $contactUserId);
    public function findByIdentifier(string $identifier);
}