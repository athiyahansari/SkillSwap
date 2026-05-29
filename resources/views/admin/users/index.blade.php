<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('User Management Overview') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-rose-700 bg-rose-100 rounded-full">
                System Administrator
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Render the Livewire component -->
            <livewire:admin-users-list />

            <!-- Action -->
            <div class="flex items-center justify-end">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
                    Back to Insights
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
