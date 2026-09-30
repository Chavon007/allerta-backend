<?php

namespace Modules\User\Repositories;
use Modules\User\Models\User;
use Modules\User\Interfaces\EmergencyContactRepositoryInterface;
use Override;

use function Laravel\Prompts\select;

class EmergencyContactRepository implements EmergencyContactRepositoryInterface{

    public function __construct(protected User $user)
    {}


	
	public function search(int $userId, string $query)
    {
        return $this->user::where("id", "!=", $userId)->where(function ($q) use ($query){
            $q->where("username", "like", "%{$query}%")->orWhere("email", "like", "%{$query}%");
        })->get();
    }

  
 
  public function addContact(int $userId, int $contactUserId, string $identifierType)
  {
     $user = $this->user::findOrFail($userId);

//    ensure the contact user actually exists

   $this->user::findOrFail($contactUserId);

   $user->emergencyContacts()->syncWithoutDetaching([$contactUserId => [
        "identifier_type" => $identifierType,
   ]]);

   return $user->emergencyContacts;
  }

  
  public function removeContactList(int $userId, int $contactUserId)
  {
   $user = $this->user::findOrFail($userId);

   return  $user->emergencyContacts()->detach($contactUserId) > 0;
  }

  
  public function findByIdentifier(string $identifier)
  {
   return $this->user::where("username", $identifier)->orWhere("email", $identifier)->first();
  }


	public function fetchContacts(int $userId)
    {
        return $this->user::findOrFail($userId)
        ->emergencyContacts()->select('users.id', 'users.full_name', 'users.username', 'users.email')
        ->get()->map(function ($contact){
             $identifierType = $contact->pivot->identifier_type;

            return [
                'id' => $contact->id,
                'full_name' => $contact->full_name,
                'identifier' => $identifierType === 'email'
                    ? $contact->email
                    : $contact->username,
                'identifier_type' => $identifierType,
            ];
        });
    }
}