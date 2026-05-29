<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TutorResource extends JsonResource
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
            'name' => $this->user->name,
            'bio' => $this->bio,
            'hourly_rate' => $this->hourly_rate,
            'education' => $this->education,
            'experience' => $this->experience,
            'verification_status' => $this->verification_status,
            'profile_photo_url' => $this->profile_photo_url,
            'subjects' => $this->whenLoaded('subjects', function () {
                return $this->subjects->pluck('name');
            }),
        ];
    }
}
