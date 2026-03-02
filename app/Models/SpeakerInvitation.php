<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeakerInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'summit_id',
        'token',
        'status',
        'full_name',
        'phone',
        'email',
        'organization',
        'role_title',
        'state',
        'preferred_location',
        'session_type',
        'speaking_topic',
        'bio',
        'profile_photo_path',
        'physical_attendance',
        'confirmed_at',
        'registered_user_id',
    ];

    protected $casts = [
        'physical_attendance' => 'boolean',
        'confirmed_at' => 'datetime',
    ];

    public function summit()
    {
        return $this->belongsTo(Summit::class);
    }

    public function registeredUser()
    {
        return $this->belongsTo(User::class, 'registered_user_id');
    }
}
