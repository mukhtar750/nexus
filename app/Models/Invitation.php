<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'summit_id',
        'status',
        'invited_by',
        'token',
        'invite_type',
        // Delegate confirmation fields
        'full_name',
        'phone',
        'email',
        'organization',
        'role_title',
        'state',
        'preferred_location',
        'areas_of_interest',
        'physical_attendance',
        'how_received_invitation',
        'confirmed_at',
    ];

    protected $casts = [
        'physical_attendance' => 'boolean',
        'confirmed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function summit(): BelongsTo
    {
        return $this->belongsTo(Summit::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
