<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company',
        'role',
        'user_type',
        'bio',
        'avatar_url',
        'status',
        'business_name',
        'business_address',
        'year_established',
        'business_structure',
        'cac_number',
        'cac_certificate_path',
        'product_category',
        'registered_with_cac',
        'exported_before',
        'registered_with_nepc',
        'nepc_status',
        'nepc_certificate_path',
        'recent_export_activity',
        'commercial_scale',
        'packaged_for_retail',
        'regulatory_registration',
        'engaged_logistics',
        'received_inquiries',
        'production_location',
        'production_compliant',
        'production_capacity',
        'active_channels',
        'sales_model',
        'export_objective',
        'haccp_certificate_path',
        'fda_certificate_path',
        'halal_certificate_path',
        'son_certificate_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role Relationship
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // Helper to check for specific role
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    // Helper to check for multiple roles (OR check)
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
