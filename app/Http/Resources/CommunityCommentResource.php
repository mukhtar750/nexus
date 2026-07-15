<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'community_post_id' => $this->community_post_id,
            'parent_id' => $this->parent_id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar_url' => $this->user->avatar_url,
                'role' => $this->user->role,
            ],
            'content' => $this->content,
            'reports_count' => (int)$this->reports_count,
            'replies' => CommunityCommentResource::collection($this->whenLoaded('replies')),
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
