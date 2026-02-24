<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Question extends Model
{
    protected $fillable = ['event_id', 'user_id', 'content', 'is_approved'];

    protected $with = ['user'];
    
    protected $appends = ['upvote_count', 'is_upvoted_by_me'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function upvotes()
    {
        return $this->hasMany(QuestionUpvote::class);
    }

    public function user_upvote()
    {
        return $this->hasOne(QuestionUpvote::class)->where('user_id', Auth::id());
    }

    public function getUpvoteCountAttribute()
    {
        return $this->upvotes()->count();
    }

    public function getIsUpvotedByMeAttribute()
    {
        return $this->user_upvote()->exists();
    }
}
