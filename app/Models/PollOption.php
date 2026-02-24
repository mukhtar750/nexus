<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    protected $fillable = ['poll_id', 'option_text'];

    protected $appends = ['vote_count', 'percentage'];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function getVoteCountAttribute()
    {
        // Use preloaded count if available (from withCount)
        if (isset($this->attributes['votes_count'])) {
            return $this->attributes['votes_count'];
        }
        return $this->votes()->count();
    }

    public function getPercentageAttribute()
    {
        // Use the votes_count if it's been loaded via withCount
        if (isset($this->attributes['votes_count'])) {
            $myVotes = $this->attributes['votes_count'];
            // We need total votes from parent, but to avoid circular reference
            // we'll calculate it from all options if possible
            return 0; // Will be calculated properly in controller/response
        }

        $total = $this->poll->votes()->count();
        if ($total == 0)
            return 0;
        return round(($this->votes()->count() / $total) * 100);
    }
}
