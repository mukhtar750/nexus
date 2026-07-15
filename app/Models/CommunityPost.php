<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CommunityPost extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'is_pinned',
        'reports_count',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'reports_count' => 'integer',
    ];

    protected $appends = [
        'is_liked',
        'likes_count',
        'comments_count',
        'user_voted_option_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(CommunityComment::class);
    }

    public function likes()
    {
        return $this->hasMany(CommunityLike::class);
    }

    public function pollOptions()
    {
        return $this->hasMany(CommunityPollOption::class);
    }

    public function pollVotes()
    {
        return $this->hasMany(CommunityPollVote::class);
    }

    public function getIsLikedAttribute()
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function getUserVotedOptionIdAttribute()
    {
        if (!Auth::check()) {
            return null;
        }
        $vote = $this->pollVotes()->where('user_id', Auth::id())->first();
        return $vote ? $vote->community_poll_option_id : null;
    }
}
