<div class="relative" x-data="{ open: false }" wire:poll.10s>
    <!-- Bell Icon Trigger -->
    <button @click="open = !open" class="relative p-2 text-slate-500 hover:text-slate-700 focus:outline-none transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($this->unreadCount > 0)
            <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-500 rounded-full">
                {{ $this->unreadCount > 99 ? '99+' : $this->unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" @click.away="open = false" style="display: none;"
         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-slate-100 overflow-hidden z-50 transition-all transform origin-top-right">
        
        <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-sm font-semibold text-slate-800">Notifications</h3>
            @if($this->unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                    Mark all as read
                </button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($this->notifications as $notification)
                <div class="px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 {{ $notification->read_at ? 'opacity-75' : 'bg-indigo-50/30' }}">
                    <div class="flex justify-between items-start gap-2">
                        <a href="{{ $notification->data['url'] ?? '#' }}" wire:click="markAsRead('{{ $notification->id }}')" class="flex-1 block">
                            <p class="text-sm text-slate-800 {{ $notification->read_at ? '' : 'font-medium' }}">
                                {{ $notification->data['message'] }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </a>
                        @if(!$notification->read_at)
                            <button wire:click="markAsRead('{{ $notification->id }}')" class="text-indigo-600 hover:text-indigo-800 p-1 rounded-full hover:bg-indigo-100 transition-colors" title="Mark as read">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-4 py-6 text-center text-slate-500">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <p class="text-sm">No notifications yet.</p>
                </div>
            @endforelse
        </div>
        
        @if($this->notifications->count() > 0)
            <div class="px-4 py-2 border-t border-slate-100 bg-slate-50 text-center">
                <span class="text-xs text-slate-500">Showing recent notifications</span>
            </div>
        @endif
    </div>
</div>
