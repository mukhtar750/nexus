<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'title',
        'avatar_url',
        'bio',
        'schedule_time',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
