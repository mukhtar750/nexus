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
        // Section C (Rigorous)
        'business_address',
        'year_established',
        'business_structure',
        'cac_number',
        'cac_certificate',
        'nepc_certificate',
        'haccp_certificate',
        'fda_certificate',
        'halal_certificate',
        'son_certificate',
        'production_location',
        'production_compliant',
        'production_capacity',
        'active_channels',
        'sales_model',
        'export_objective',
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

    protected $appends = [
        'certificate_urls',
    ];

    public function summit()
    {
        return $this->belongsTo(Summit::class);
    }

    public function registeredUser()
    {
        return $this->belongsTo(User::class, 'registered_user_id');
    }

    /**
     * Determine if this EOI meets the criteria for automatic selection.
     */
    public function shouldAutoSelect(): bool
    {
        // 1. Mandatory Documents (must have CAC and NEPC)
        if (!$this->cac_certificate || !$this->nepc_certificate) {
            return false;
        }

        // 2. Export Experience (Currently exporting or exported before)
        $validExportStatus = ['currently_exporting', 'exported_before'];
        if (!in_array($this->export_status, $validExportStatus)) {
            return false;
        }

        // 3. Production Readiness & Compliance
        if (!$this->production_compliant) {
            return false;
        }

        // 4. Commercial Scale
        if (!$this->commercial_scale) {
            return false;
        }

        return true;
    }

    /**
     * Get absolute URLs for all uploaded certificates.
     */
    public function getCertificateUrlsAttribute(): array
    {
        $fields = [
            'cac_certificate',
            'nepc_certificate',
            'haccp_certificate',
            'fda_certificate',
            'halal_certificate',
            'son_certificate',
        ];

        $urls = [];
        foreach ($fields as $field) {
            if ($this->{$field}) {
                $urls[$field] = \Illuminate\Support\Facades\Storage::url($this->{$field});
            }
        }

        return $urls;
    }
}
