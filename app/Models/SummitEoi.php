<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SummitEoi extends Model
{
    use HasFactory;

    protected $table = 'summit_eois';

    protected $fillable = [
        'summit_id',
        // Section A
        'full_name',
        'phone',
        'email',
        'business_name',
        'state',
        'preferred_location',
        'how_heard',
        // Section B
        'sector',
        'primary_products',
        'cac_registration',
        'nepc_registration',
        'export_status',
        'recent_export_value',
        // Section C
        'commercial_scale',
        'regulatory_registration',
        'regulatory_body',
        'certifications',
        'seminar_goals',
        // System
        'status',
        'rejection_reason',
        'selected_at',
        'registration_token',
        'registered_user_id',
    ];

    protected $casts = [
        'commercial_scale' => 'boolean',
        'regulatory_registration' => 'boolean',
        'certifications' => 'array',
        'seminar_goals' => 'array',
        'selected_at' => 'datetime',
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
