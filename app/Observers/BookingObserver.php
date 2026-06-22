<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        $this->logEvent('created', $booking);
    }

    public function updated(Booking $booking): void
    {
        $this->logEvent('updated', $booking, $booking->getOriginal());
    }

    public function deleted(Booking $booking): void
    {
        $this->logEvent('deleted', $booking, $booking->getOriginal());
    }

    protected function logEvent(string $eventType, Booking $booking, ?array $oldValues = null): void
    {
        AuditLog::create([
            'event_type' => $eventType,
            'model_type' => Booking::class,
            'model_id'   => $booking->id,
            'user_id'    => Auth::id(),
            'old_values' => $oldValues,
            'new_values' => $booking->getAttributes(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
