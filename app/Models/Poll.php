<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Poll extends Model
{
    protected $fillable = ['event_id', 'question', 'is_active', 'start_time', 'end_time'];

    protected $casts = [
        'is_active' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Removed $with to prevent circular relationship
    // protected $with = ['options'];

    protected $appends = ['user_voted_option_id', 'total_votes'];

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function user_vote()
    {
        if (!Auth::check()) {
            return null;
        }
        return $this->hasOne(PollVote::class)->where('user_id', Auth::id());
    }

    public function getUserVotedOptionIdAttribute()
    {
        $vote = $this->user_vote;
        return $vote?->poll_option_id;
    }

    public function getTotalVotesAttribute()
    {
        // Use preloaded count if available (from withCount)
        if (isset($this->attributes['votes_count'])) {
            return $this->attributes['votes_count'];
        }
        return $this->votes()->count();
    }
}
