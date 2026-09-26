<?php

namespace Modules\User\Repositories;
use Modules\User\Models\User;
use Modules\User\Interfaces\EmergencyContactRepositoryInterface;
use Override;

class EmergencyContactRepository implements EmergencyContactRepositoryInterface{

    public function __construct(protected User $user)
    {}


	
	public function search(int $userId, string $query)
    {
        return $this->user::where("id", "!=", $userId)->where(function ($q) use ($query){
            $q->where("username", "like", "%{$query}%")->orWhere("email", "like", "%{$query}%");
        })->get();
    }

  
 
  public function addContact(int $userId, int $contactUserId)
  {
     $user = $this->user::findOrFail($userId);

//    ensure the contact user actually exists

   $this->user::findOrFail($contactUserId);

   $user->emergencyContacts()->syncWithoutDetaching([$contactUserId]);

   return $user->emergencyContacts;
  }

  
  public function removeContactList(int $userId, int $contactUserId)
  {
   $user = $this->user::findOrFail($userId);

   $user->emergencyContacts()->detach($contactUserId);

   return $user->emergencyContacts;
  }

  
  public function findByIdentifier(string $identifier)
  {
   return $this->user::where("username", $identifier)->orWhere("email", $identifier)->first();
  }
}