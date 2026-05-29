<div>
    <div class="space-y-6">
        <!-- Search and Filter Bar -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <!-- Search Input -->
                <div class="flex-1 w-full">
                    <label for="search" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Search Users</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" id="search" placeholder="Search by name or email..." class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pl-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Role Filter -->
                <div class="w-full md:w-48">
                    <label for="role" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Filter by Role</label>
                    <select wire:model.live="role" id="role" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">All Roles</option>
                        <option value="tutor">Tutor (Guide)</option>
                        <option value="learner">Learner</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 items-center w-full md:w-auto">
                    <div wire:loading class="text-indigo-600 text-sm font-semibold animate-pulse">
                        Filtering...
                    </div>
                    @if(!empty($search) || !empty($role))
                        <button wire:click="clearFilters" class="flex-1 md:flex-none inline-flex justify-center items-center px-4 py-2.5 border border-slate-200 text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition">
                            Reset
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Users Detailed Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">All Registered Users</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Overview of user profiles, activity metrics, and platform financial contributions.</p>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                    {{ $users->total() }} Total
                </span>
            </div>

            <div class="overflow-x-auto" wire:loading.class="opacity-50 transition-opacity">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                        <tr>
                            <th class="px-6 py-4">User Info</th>
                            <th class="px-6 py-4">Status & Skills</th>
                            <th class="px-6 py-4 text-center">Completed Sessions</th>
                            <th class="px-6 py-4 text-right">Financial Summary</th>
                            <th class="px-6 py-4">Joined Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr wire:key="user-{{ $user->id }}" class="hover:bg-slate-50/50 transition-colors">
                                <!-- User Info -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <img class="w-10 h-10 rounded-full object-cover shadow-sm" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                        <div>
                                            <div class="font-extrabold text-slate-800 text-sm">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-400 font-medium mt-0.5">{{ $user->email }}</div>
                                            <div class="mt-1">
                                                @if($user->role === 'admin')
                                                    <span class="px-2 py-0.5 text-[10px] font-bold text-rose-700 bg-rose-50 rounded-full border border-rose-100">Admin</span>
                                                @elseif($user->role === 'tutor')
                                                    <span class="px-2 py-0.5 text-[10px] font-bold text-purple-700 bg-purple-50 rounded-full border border-purple-100">Tutor</span>
                                                @else
                                                    <span class="px-2 py-0.5 text-[10px] font-bold text-blue-700 bg-blue-50 rounded-full border border-blue-100">Learner</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status & Skills -->
                                <td class="px-6 py-4">
                                    @if($user->role === 'tutor' && $user->tutorProfile)
                                        <div class="space-y-1">
                                            <div class="flex items-center space-x-1.5">
                                                <span class="text-xs font-semibold text-slate-500">Status:</span>
                                                @if($user->tutorProfile->verification_status === 'verified')
                                                    <span class="px-1.5 py-0.5 text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 rounded">Verified</span>
                                                @elseif($user->tutorProfile->verification_status === 'pending')
                                                    <span class="px-1.5 py-0.5 text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100 rounded">Pending</span>
                                                @else
                                                    <span class="px-1.5 py-0.5 text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-100 rounded">Rejected</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-slate-500 font-medium">Rate: ${{ number_format($user->tutorProfile->hourly_rate, 2) }}/hr</div>
                                            <div class="flex flex-wrap gap-1 mt-1 max-w-xs">
                                                @forelse($user->tutorProfile->subjects as $subject)
                                                    <span class="px-1.5 py-0.5 text-[10px] font-semibold bg-indigo-50 text-indigo-700 rounded border border-indigo-100/50">
                                                        {{ $subject->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-[10px] text-slate-400 italic">No skills added</span>
                                                @endforelse
                                            </div>
                                        </div>
                                    @elseif($user->role === 'learner')
                                        <span class="text-xs text-slate-500 font-medium italic">Standard Learner Account</span>
                                    @elseif($user->role === 'admin')
                                        <span class="text-xs text-slate-500 font-medium italic">System Administrator</span>
                                    @endif
                                </td>

                                <!-- Completed Sessions -->
                                <td class="px-6 py-4 text-center">
                                    @if($user->role === 'tutor' && $user->tutorProfile)
                                        <span class="font-bold text-slate-800 text-sm">
                                            {{ $user->tutorProfile->bookings->count() }}
                                        </span>
                                        <div class="text-[10px] text-slate-400 mt-0.5">sessions taught</div>
                                    @elseif($user->role === 'learner')
                                        <span class="font-bold text-slate-800 text-sm">
                                            {{ $user->bookings->count() }}
                                        </span>
                                        <div class="text-[10px] text-slate-400 mt-0.5">sessions booked</div>
                                    @else
                                        <span class="text-slate-400 font-medium">-</span>
                                    @endif
                                </td>

                                <!-- Financial Summary -->
                                <td class="px-6 py-4 text-right">
                                    @if($user->role === 'tutor' && $user->tutorProfile)
                                        <div class="text-xs text-slate-600 font-bold">
                                            Earned: <span class="text-purple-600">${{ number_format($user->tutorProfile->bookings->sum('tutor_earnings'), 2) }}</span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">
                                            Platform Fees: <span class="text-green-600">+${{ number_format($user->tutorProfile->bookings->sum('platform_fee'), 2) }}</span>
                                        </div>
                                    @elseif($user->role === 'learner')
                                        <div class="text-xs text-slate-600 font-bold">
                                            Spent: <span class="text-indigo-600">${{ number_format($user->bookings->sum('hourly_rate'), 2) }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 font-medium">-</span>
                                    @endif
                                </td>

                                <!-- Joined Date -->
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    No users found matching your search criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-6 bg-slate-50 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
