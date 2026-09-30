<?php
namespace Modules\User\Services;

use Modules\User\Interfaces\EmergencyContactRepositoryInterface;
use Modules\User\Interfaces\UserRepositoryInterface;
use Illuminate\Validation\ValidationException;

class EmergencyContactService
{
    public function __construct(
        protected EmergencyContactRepositoryInterface $emergencyContactRepositoryInterface,
        protected UserRepositoryInterface $userRepository
    ) {}

    public function searchContact(int $userId, string $query)
    {
        $user = $this->userRepository->find($userId);

        if (!$user) {
            throw ValidationException::withMessages(["user" => "User does not exist"]);
        }

        return $this->emergencyContactRepositoryInterface->search($userId, $query);
    }

   public function addContact(int $userId, string $identifier)
{
    $user = $this->userRepository->find($userId);

    if (!$user) {
        throw ValidationException::withMessages([
            "user" => "User does not exist"
        ]);
    }

    $contactUser = $this->emergencyContactRepositoryInterface
        ->findByIdentifier($identifier);

    if (!$contactUser) {
        throw ValidationException::withMessages([
            "identifier" => "No user found with that username or email"
        ]);
    }

    if ($userId === $contactUser->id) {
        throw ValidationException::withMessages([
            "identifier" => "You cannot add yourself as an emergency contact"
        ]);
    }

    $type = filter_var($identifier, FILTER_VALIDATE_EMAIL)
        ? "email"
        : "username";

    return $this->emergencyContactRepositoryInterface->addContact(
        $userId,
        $contactUser->id,
        $type
    );
}

  public function removeContact(int $userId, int $contactUserId)
{
    $removed = $this->emergencyContactRepositoryInterface
        ->removeContactList($userId, $contactUserId);

    if (!$removed) {
        throw ValidationException::withMessages([
            "contact" => "Contact has already been removed",
        ]);
    }

    return true;
}
    public function fetchContacts(int $userId, ){
        $user = $this->userRepository->find($userId);

        if(!$user){
            throw ValidationException::withMessages(["user" => "User does not exist"]);
        }

        return $this->emergencyContactRepositoryInterface->fetchContacts($userId);
    }
}