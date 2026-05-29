<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;

class NotificationsDropdown extends Component
{
    #[Computed]
    public function notifications()
    {
        if (!auth()->check()) return collect();
        return auth()->user()->notifications()->take(10)->get();
    }

    #[Computed]
    public function unreadCount()
    {
        if (!auth()->check()) return 0;
        return auth()->user()->unreadNotifications()->count();
    }

    public function markAsRead($id)
    {
        if (!auth()->check()) return;
        
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        if (!auth()->check()) return;
        
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.notifications-dropdown');
    }
}
