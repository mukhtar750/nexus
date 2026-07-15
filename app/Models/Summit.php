<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Summit extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'city',
        'zone',
        'date',
        'venue',
        'is_active',
        'hasHighlights',
        'highlights_data',
        'is_eoi_open',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hasHighlights' => 'boolean',
        'highlights_data' => 'array',
        'is_eoi_open' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
