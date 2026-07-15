<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityPollOption extends Model
{
    protected $fillable = [
        'community_post_id',
        'option_text',
    ];

    protected $appends = ['votes_count'];

    public function post()
    {
        return $this->belongsTo(CommunityPost::class, 'community_post_id');
    }

    public function votes()
    {
        return $this->hasMany(CommunityPollVote::class, 'community_poll_option_id');
    }

    public function getVotesCountAttribute()
    {
        return $this->votes()->count();
    }
}
