<?php

namespace Modules\User\Interfaces;

interface EmergencyContactRepositoryInterface{
    public function search(int $userId, string $query);
    public function addContact(int $userId, int $contactUserId, string $identifierType);
    public function removeContactList(int $userId, int $contactUserId);
    public function findByIdentifier(string $identifier);
    public function fetchContacts(int $userId);
}