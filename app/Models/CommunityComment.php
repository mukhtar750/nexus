<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityComment extends Model
{
    protected $fillable = [
        'community_post_id',
        'user_id',
        'parent_id',
        'content',
        'reports_count',
    ];

    protected $casts = [
        'reports_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(CommunityPost::class, 'community_post_id');
    }

    public function parent()
    {
        return $this->belongsTo(CommunityComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(CommunityComment::class, 'parent_id')->with('user');
    }
}
