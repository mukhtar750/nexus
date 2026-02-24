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
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
