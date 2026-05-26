<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-rose-700 bg-rose-100 rounded-full">
                System Administrator
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-rose-600 via-pink-700 to-red-800 rounded-3xl p-8 md:p-12 shadow-xl relative overflow-hidden text-white">
                <div class="absolute right-0 bottom-0 top-0 w-1/3 opacity-10 pointer-events-none">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0,100 C30,40 70,60 100,0 L100,100 Z" fill="currentColor"></path>
                    </svg>
                </div>
                <div class="relative z-10 space-y-4">
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                        Welcome back, {{ auth()->user()->name }}! 👑
                    </h1>
                    <p class="text-rose-100 text-lg max-w-xl">
                        SkillSwap system management portal. Verify tutor credentials, resolve tickets, and audit platform statistics.
                    </p>
                    <div class="pt-2">
                        <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-xl text-rose-700 bg-white hover:bg-rose-50 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150">
                            System Health
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Platform Revenue -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">+8.2% this month</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">$12,450.00</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Platform Revenue</p>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">Active</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">1,245</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Registered Users</p>
                    </div>
                </div>

                <!-- Active Bookings -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-sky-50 text-sky-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full">Ongoing</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">342</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Active Bookings</p>
                    </div>
                </div>

                <!-- Tutor Applications -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-full">Requires Action</span>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800">{{ $pendingTutors->count() }}</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Pending Tutor Approvals</p>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Pending Approvals list -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-slate-800">Pending Tutor Applications</h2>
                        </div>
                        
                        <div class="divide-y divide-slate-100">
                            @forelse ($pendingTutors as $tutor)
                                <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 first:pt-0 last:pb-0">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex-shrink-0 flex items-center justify-center font-bold text-lg border border-amber-100/50">
                                            {{ strtoupper(substr($tutor->user->name ?? 'T', 0, 2)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800">{{ $tutor->user->name }}</h4>
                                            <p class="text-sm text-slate-500">Subjects: {{ $tutor->subjects->pluck('name')->implode(', ') ?: 'None' }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Rate: ${{ number_format($tutor->hourly_rate, 2) }}/hr | Edu: {{ $tutor->education }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between sm:justify-end gap-4">
                                        <div class="text-left sm:text-right">
                                            <p class="text-xs font-semibold text-slate-500">Applied {{ $tutor->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <form action="{{ route('admin.tutors.verify', $tutor) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to VERIFY this tutor?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm hover:shadow transition">
                                                    Verify
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.tutors.reject', $tutor) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to REJECT this tutor?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-sm hover:shadow transition">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-slate-500">No pending tutor applications.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Verified & Rejected Tutors -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">
                        <h2 class="text-lg font-bold text-slate-800">Verified & Rejected Tutors</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg inline-block mb-3 uppercase tracking-wider">Verified Tutors</h3>
                                <div class="divide-y divide-slate-100 max-h-60 overflow-y-auto pr-2">
                                    @forelse ($verifiedTutors as $tutor)
                                        <div class="py-3 flex items-center justify-between gap-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex-shrink-0 flex items-center justify-center font-bold text-sm">
                                                    {{ strtoupper(substr($tutor->user->name ?? 'T', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-slate-800 text-sm">{{ $tutor->user->name }}</h4>
                                                    <p class="text-xs text-slate-500">{{ $tutor->subjects->pluck('name')->implode(', ') ?: 'None' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <form action="{{ route('admin.tutors.revert', $tutor) }}" method="POST" class="inline" onsubmit="return confirm('WARNING: Are you sure you want to REVERT this tutor\'s status back to pending? This will temporarily hide them from some marketplace features.');">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg border border-amber-200 transition">
                                                        Revert to Pending
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.tutors.reject', $tutor) }}" method="POST" class="inline" onsubmit="return confirm('CAUTION: Are you sure you want to change this tutor\'s status to REJECTED?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg border border-rose-200 transition">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 py-2 italic">No verified tutors yet.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="border-t border-slate-100 pt-4">
                                <h3 class="text-xs font-bold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-lg inline-block mb-3 uppercase tracking-wider">Rejected Tutors</h3>
                                <div class="divide-y divide-slate-100 max-h-60 overflow-y-auto pr-2">
                                    @forelse ($rejectedTutors as $tutor)
                                        <div class="py-3 flex items-center justify-between gap-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex-shrink-0 flex items-center justify-center font-bold text-sm">
                                                    {{ strtoupper(substr($tutor->user->name ?? 'T', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-slate-800 text-sm">{{ $tutor->user->name }}</h4>
                                                    <p class="text-xs text-slate-500">{{ $tutor->subjects->pluck('name')->implode(', ') ?: 'None' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <form action="{{ route('admin.tutors.revert', $tutor) }}" method="POST" class="inline" onsubmit="return confirm('WARNING: Are you sure you want to REVERT this tutor\'s status back to pending?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg border border-amber-200 transition">
                                                        Revert to Pending
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.tutors.verify', $tutor) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to change this tutor\'s status to VERIFIED?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg border border-emerald-200 transition">
                                                        Verify
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 py-2 italic">No rejected tutors yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Admin settings / actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">
                    <h2 class="text-lg font-bold text-slate-800">Administrative Tools</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <a href="#" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                            <div class="p-2.5 rounded-lg bg-rose-50 text-rose-600 mr-4 group-hover:bg-rose-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">User Management</h4>
                                <p class="text-xs text-slate-500">View and edit user settings</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                            <div class="p-2.5 rounded-lg bg-red-50 text-red-600 mr-4 group-hover:bg-red-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Dispute Resolutions</h4>
                                <p class="text-xs text-slate-500">Refunds, reports & flags</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-center p-3 rounded-xl hover:bg-slate-50 border border-slate-100 transition-colors group">
                            <div class="p-2.5 rounded-lg bg-slate-100 text-slate-600 mr-4 group-hover:bg-slate-700 group-hover:text-white transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">System Settings</h4>
                                <p class="text-xs text-slate-500">Feature flags & API keys</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>