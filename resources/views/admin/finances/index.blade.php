<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Platform Finances') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-rose-700 bg-rose-100 rounded-full">
                System Administrator
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <livewire:admin-finances />
        </div>
    </div>
</x-app-layout>
