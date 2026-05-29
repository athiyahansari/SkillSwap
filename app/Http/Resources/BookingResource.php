<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'session_date' => $this->session_date,
            'session_time' => $this->session_time,
            'status' => $this->status,
            'hourly_rate' => $this->hourly_rate,
            'payment_status' => $this->payment_status,
            'notes' => $this->notes,
            'subject' => $this->whenLoaded('subject', function () {
                return [
                    'id' => $this->subject->id,
                    'name' => $this->subject->name,
                ];
            }),
            'tutor' => $this->whenLoaded('tutorProfile', function () {
                return [
                    'id' => $this->tutorProfile->id,
                    'name' => $this->tutorProfile->user->name ?? null,
                    'profile_photo_url' => $this->tutorProfile->profile_photo_url,
                ];
            }),
        ];
    }
}
