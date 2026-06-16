<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('System Audit Logs') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-purple-700 bg-purple-100 rounded-full">
                Admin Area
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-6 md:p-8">
                
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-slate-800">Security & Compliance</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        This dashboard provides an immutable record of authentication and system events securely stored in MongoDB.
                        Your access to this page has been logged.
                    </p>
                </div>

                @livewire('system-audit-log')
                
            </div>
        </div>
    </div>
</x-app-layout>
