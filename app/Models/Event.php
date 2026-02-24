<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'cover_image_url', // raw DB path
        'requires_invitation',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'requires_invitation' => 'boolean',
    ];

    // Always include computed attributes in JSON
    protected $appends = ['cover_image_url_full'];

    /**
     * Returns a full URL to the cover image for API/Flutter consumption.
     */
    public function getCoverImageUrlFullAttribute()
    {
        // Return null if no image
        if (!$this->cover_image_url) {
            return null;
        }

        // If it's already a full URL (e.g. from external source or legacy data), return it
        if (filter_var($this->cover_image_url, FILTER_VALIDATE_URL)) {
            return $this->cover_image_url;
        }

        // Convert storage path to full URL
        return asset('storage/' . $this->cover_image_url);
    }

    public function sessions()
    {
        return $this->hasMany(EventSession::class);
    }

    public function speakers()
    {
        return $this->hasMany(Speaker::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function polls()
    {
        return $this->hasMany(Poll::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
