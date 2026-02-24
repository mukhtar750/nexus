<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $isRegistered = false;
        $ticket = null;

        if ($user) {
            $ticket = $this->tickets()->where('user_id', $user->id)->first();
            $isRegistered = $ticket !== null;
            // Log for debugging
            // \Log::info("EventResource: User {$user->id} viewing Event {$this->id}. Registered: " . ($isRegistered ? 'Yes' : 'No'));
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'location' => $this->location,
            'cover_image_url' => $this->cover_image_url_full,
            'sessions' => $this->whenLoaded('sessions'),
            'speakers' => $this->whenLoaded('speakers'),
            'is_registered' => $isRegistered,
            'requires_invitation' => $this->requires_invitation,
            'ticket' => $ticket,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
