<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use App\Models\AuditLog;

class AuthEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUserLogin(Login $event): void
    {
        $this->logEvent('user_login', $event->user);
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout(Logout $event): void
    {
        $this->logEvent('user_logout', $event->user);
    }

    /**
     * Log the event to MongoDB.
     */
    protected function logEvent(string $eventType, $user): void
    {
        AuditLog::create([
            'event_type' => $eventType,
            'model_type' => get_class($user),
            'model_id'   => $user->id,
            'user_id'    => $user->id,
            'old_values' => null,
            'new_values' => ['email' => $user->email, 'role' => $user->role],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            Login::class,
            [AuthEventSubscriber::class, 'handleUserLogin']
        );

        $events->listen(
            Logout::class,
            [AuthEventSubscriber::class, 'handleUserLogout']
        );
    }
}
