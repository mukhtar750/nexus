<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalVotes = $this->pollVotes()->count();

        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar_url' => $this->user->avatar_url,
                'role' => $this->user->role,
            ],
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type,
            'is_pinned' => (bool)$this->is_pinned,
            'reports_count' => (int)$this->reports_count,
            'is_liked' => (bool)$this->is_liked,
            'likes_count' => (int)$this->likes_count,
            'comments_count' => (int)$this->comments_count,
            'poll_options' => $this->type === 'poll' ? $this->pollOptions->map(function ($option) use ($totalVotes) {
                $optionVotes = $option->votes_count;
                $percentage = $totalVotes > 0 ? round(($optionVotes / $totalVotes) * 100) : 0;
                return [
                    'id' => $option->id,
                    'option_text' => $option->option_text,
                    'votes_count' => $optionVotes,
                    'percentage' => $percentage,
                ];
            }) : null,
            'total_votes' => $this->type === 'poll' ? $totalVotes : 0,
            'user_voted_option_id' => $this->type === 'poll' ? $this->user_voted_option_id : null,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
