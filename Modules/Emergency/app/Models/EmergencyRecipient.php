<?php

namespace Modules\Emergency\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

class EmergencyRecipient extends Model{
    protected $fillable = [
        "emergency_id",
        "contact_user_id",
        "status",
    ];

    public function emergency():BelongsTo{
        return $this->belongsTo(Emergency::class);
    }

    public function contact(): BelongsTo{
        return $this->belongsTo(User::class, "contact_user_id");
    }
}